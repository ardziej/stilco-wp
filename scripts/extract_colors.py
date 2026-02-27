import fitz
import collections

doc = fitz.open("docs/Stilco_materace_web_proposal.pdf")
colors = []
for page in doc:
    for item in page.get_drawings():
        if "color" in item and item["color"]:
            colors.append(item["color"])
        if "fill" in item and item["fill"]:
            colors.append(item["fill"])

print("Found colors in drawings:", collections.Counter([str(c) for c in colors]).most_common(10))

images = []
for page in doc:
    for img in page.get_images(full=True):
        images.append(img)
print(f"Found {len(images)} images.")
