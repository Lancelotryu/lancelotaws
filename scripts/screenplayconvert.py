#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import os
import zipfile
import xml.etree.ElementTree as ET
import re
from unidecode import unidecode

CLASS_TAG_MAP = {
    'titre':       ('h1', 'playtitle'),
    'tacte':       ('h2', 'playact'),
    'tscne':       ('h3', 'playscene'),
    'slugline':    ('h3', 'slugline'),
}

CLASS_RENAME_MAP = {
    'personnage':   'tperso',
    'dialogue':     'tdialogue',
    'parenthtique': 'tdidascalies',
}

SCRIPT_DIR = os.path.abspath(os.path.dirname(__file__))

#Path to the input folder
INPUT_DIR = os.path.normpath(os.path.join(
    SCRIPT_DIR,
    '..', '..', '..',
    'Écriture', 'scripts'
))

#Path to the output folder
OUTPUT_DIR = os.path.normpath(os.path.join(
    SCRIPT_DIR,
    '..', '..',
    'Web', 'Xampp', 'htdocs', 'lancelot', 'data'
))

#irrelevant styles to exclude
EXCLUDED_STYLES = {
    'adresseexpditeur',
    'destinataire',
    'objet',
    'salutations',
    'corpsdetexte',
    'formuledepolitesse',
    'signature',
    'annexe'
}

#script made from scratch... except for Word mapping =)
NS = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}

def slugify(text, maxlen=6):
#transform the full name in a short 6 char name
    s = unidecode(text)
    s = re.sub(r'[^a-zA-Z0-9]+', '-', s).lower().strip('-')
    return (s[:maxlen] if s else 'file') or 'file'
#extract the XML file from the .docx
def extract_document_xml(docx_path):
    with zipfile.ZipFile(docx_path, 'r') as z:
        with z.open('word/document.xml') as doc_xml:
            return doc_xml.read()
#Gather the paragraphs
def get_paragraphs(xml_bytes):
    root = ET.fromstring(xml_bytes)
    paras = []
    for p in root.findall('.//w:p', NS):
        style = None
        ppr = p.find('w:pPr', NS)
        if ppr is not None:
            ps = ppr.find('w:pStyle', NS)
            if ps is not None:
                style = ps.attrib.get(f'{{{NS["w"]}}}val')
        texts = [t.text for t in p.findall('.//w:t', NS) if t.text]
        text = ''.join(texts).strip()
        if text:
            paras.append((style, text))
    return paras

#We do some class renaming here, for a clean CSS
def style_to_tag_and_class(style):

    cls = (style or 'Normal').replace(' ', '-').lower()

    if cls in CLASS_TAG_MAP:
        tag, css = CLASS_TAG_MAP[cls]
        return tag, f' class="{css}"'

    if cls in CLASS_RENAME_MAP:
        new_cls = CLASS_RENAME_MAP[cls]
        return 'p', f' class="{new_cls}"'

    if style and style.lower().startswith('heading'):
        num = ''.join(filter(str.isdigit, style))
        try:
            lvl = max(1, min(int(num), 6))
            return f'h{lvl}', ''
        except ValueError:
            pass

    return 'p', f' class="{cls}"'

def convert_docx_to_php(input_path):
    # basic name
    base = os.path.splitext(os.path.basename(input_path))[0]
    slug = slugify(base, maxlen=6)
    # full output path
    output_path = os.path.join(OUTPUT_DIR, slug + '.php')

    xml   = extract_document_xml(input_path)
    paras = get_paragraphs(xml)

    # creating folder if needed 
    os.makedirs(OUTPUT_DIR, exist_ok=True)

    with open(output_path, 'w', encoding='utf-8') as f:
        f.write(f'<section class="playsheet" id="{slug}">\n\n')
        for style, text in paras:
            cls = (style or 'Normal').replace(' ', '-').lower()
            # filtrer styles exclus
            if cls in EXCLUDED_STYLES:
                if cls == 'annexe' and text.startswith('Annexe'):
                    pass
                else:
                    continue
            tag, cls_attr = style_to_tag_and_class(style)
            f.write(f'<{tag}{cls_attr}>{text}</{tag}>\n')
        f.write('\n</section>\n')

    print(f"File generated → {output_path}")

def main():
    if not os.path.isdir(INPUT_DIR):
        print(f"[Erreur] Folder not found : {INPUT_DIR}")
        return

    for fname in os.listdir(INPUT_DIR):
        if fname.lower().endswith('.docx'):
            convert_docx_to_php(os.path.join(INPUT_DIR, fname))

if __name__ == '__main__':
    main()