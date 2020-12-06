
import requests
import os
import sys

css_text = ""
for filename in os.listdir('components'):
    f = open('components/'+filename, "r")
    css_text += f.read()
    f.close()

if "-m" in sys.argv:
    r = requests.post("https://cssminifier.com/raw", data={"input":css_text})
    css_minified = r.text
else:
    css_minified = css_text

f2 = open("style.min.css", "w")
f2.write(css_minified)
f2.close()
