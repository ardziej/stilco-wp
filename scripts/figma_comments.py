#!/usr/bin/env python3
"""Fetch comments from the Stilco FigJam board via the Figma REST API.

Requires FIGMA_TOKEN in the environment or .env (personal access token,
scope file_comments:read). Optional: FIGMA_FILE_KEY (defaults to the
"Stilco - strona" board).
"""

import json
import os
import sys
import urllib.request
from pathlib import Path

DEFAULT_FILE_KEY = "uQIPjHyVC5lDahjtyoUxiQ"


def load_dotenv() -> None:
    env_path = Path(__file__).resolve().parent.parent / ".env"
    if not env_path.exists():
        return
    for line in env_path.read_text().splitlines():
        line = line.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        os.environ.setdefault(key.strip(), value.strip().strip("\"'"))


def main() -> int:
    load_dotenv()
    token = os.environ.get("FIGMA_TOKEN")
    if not token:
        print("FIGMA_TOKEN not set (env or .env)", file=sys.stderr)
        return 1
    file_key = os.environ.get("FIGMA_FILE_KEY", DEFAULT_FILE_KEY)

    req = urllib.request.Request(
        f"https://api.figma.com/v1/files/{file_key}/comments",
        headers={"X-Figma-Token": token},
    )
    with urllib.request.urlopen(req) as resp:
        data = json.load(resp)

    comments = sorted(data.get("comments", []), key=lambda c: c["created_at"])
    if "--json" in sys.argv:
        print(json.dumps(comments, ensure_ascii=False, indent=2))
        return 0

    for c in comments:
        node = (c.get("client_meta") or {}).get("node_id", "-")
        status = "resolved" if c.get("resolved_at") else "open"
        reply = f" (reply to {c['parent_id']})" if c.get("parent_id") else ""
        print(f"[{c['created_at'][:16]}] {c['user']['handle']} @{node} {status}{reply}")
        print(c["message"].strip())
        print()
    return 0


if __name__ == "__main__":
    sys.exit(main())
