import sys
from gtts import gTTS

def ksj(text, filename, lang='en'):
    tts = gTTS(text=text, lang=lang)
    tts.save(filename)

if __name__ == "__main__":
    text = sys.argv[1]
    filename = sys.argv[2]
    lang = 'ar'
    ksj(text, filename, lang)