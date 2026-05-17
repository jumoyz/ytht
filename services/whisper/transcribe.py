import whisper
import sys
import os

def format_lyrics(text):
    # Simple formatting into readable blocks
    lines = text.split('. ')
    formatted = ""

    section = "[VERSE 1]\n"
    for i, line in enumerate(lines):
        if i % 4 == 0 and i != 0:
            section = "\n[CHORUS]\n" if "yeah" in line.lower() else "\n[VERSE]\n"
            formatted += section

        formatted += line.strip() + "\n"

    return formatted


def main():
    if len(sys.argv) < 2:
        print("No file provided")
        return

    audio_path = sys.argv[1]

    model = whisper.load_model("base")
    result = model.transcribe(audio_path)

    formatted = format_lyrics(result["text"])

    output_file = audio_path + ".txt"

    with open(output_file, "w", encoding="utf-8") as f:
        f.write("========================================\n")
        f.write("AUTO-GENERATED LYRICS\n")
        f.write("========================================\n\n")
        f.write(formatted)

    print(output_file)


if __name__ == "__main__":
    main()