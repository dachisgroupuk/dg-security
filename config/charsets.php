<?php
$charsets = array(
      // Commonly Used
      ////////////////////
      array('0x0030', '0x0039'), // Numbers
      array('0x0041', '0x005A'), // Uppercase
      array('0x0061', '0x007A'), // Lowercase
      array('0x0021', '0x002F'), // Punctuation
      array('0x003A', '0x0040'), // Punctuation
      array('0x005B', '0x0060'), // Punctuation
      array('0x007B', '0x007E'), // Punctuation
      // Everything Else
      ////////////////////
      array('0x0080', '0x00FF'), // Latin-1 Supplement
      array('0x0100', '0x017F'), // Latin Extended-A
      array('0x0180', '0x024F'), // Latin Extended-B
      array('0x0250', '0x02AF'), // IPA Extensions
      array('0x02B0', '0x02FF'), // Spacing Modifier Letters
      array('0x0300', '0x036F'), // Combining Diacritical Marks
      array('0x0370', '0x03FF'), // Greek
      array('0x0400', '0x04FF'), // Cyrillic
      array('0x0530', '0x058F'), // Armenian
      array('0x0590', '0x05FF'), // Hebrew
      array('0x0600', '0x06FF'), // Arabic
      array('0x0700', '0x074F'), // Syriac
      array('0x0780', '0x07BF'), // Thaana
      array('0x0900', '0x097F'), // Devanagari
      array('0x0980', '0x09FF'), // Bengali
      array('0x0A00', '0x0A7F'), // Gurmukhi
      array('0x0A80', '0x0AFF'), // Gujarati
      array('0x0B00', '0x0B7F'), // Oriya
      array('0x0B80', '0x0BFF'), // Tamil
      array('0x0C00', '0x0C7F'), // Telugu
      array('0x0C80', '0x0CFF'), // Kannada
      array('0x0D00', '0x0D7F'), // Malayalam
      array('0x0D80', '0x0DFF'), // Sinhala
      array('0x0E00', '0x0E7F'), // Thai
      array('0x0E80', '0x0EFF'), // Lao
      array('0x0F00', '0x0FFF'), // Tibetan
      array('0x1000', '0x109F'), // Myanmar
      array('0x10A0', '0x10FF'), // Georgian
      array('0x1100', '0x11FF'), // Hangul Jamo
      array('0x1200', '0x137F'), // Ethiopic
      array('0x13A0', '0x13FF'), // Cherokee
      array('0x1400', '0x167F'), // Unified Canadian Aboriginal Syllabics
      array('0x1680', '0x169F'), // Ogham
      array('0x16A0', '0x16FF'), // Runic
      array('0x1780', '0x17FF'), // Khmer
      array('0x1800', '0x18AF'), // Mongolian
      array('0x1E00', '0x1EFF'), // Latin Extended Additional
      array('0x1F00', '0x1FFF'), // Greek Extended
      array('0x2000', '0x206F'), // General Punctuation
      array('0x2070', '0x209F'), // Superscripts and Subscripts
      array('0x20A0', '0x20CF'), // Currency Symbols
      array('0x20D0', '0x20FF'), // Combining Marks for Symbols
      array('0x2100', '0x214F'), // Letterlike Symbols
      array('0x2150', '0x218F'), // Number Forms
      array('0x2190', '0x21FF'), // Arrows
      array('0x2200', '0x22FF'), // Mathematical Operators
      array('0x2300', '0x23FF'), // Miscellaneous Technical
      array('0x2400', '0x243F'), // Control Pictures
      array('0x2440', '0x245F'), // Optical Character Recognition
      array('0x2460', '0x24FF'), // Enclosed Alphanumerics
      array('0x2500', '0x257F'), // Box Drawing
      array('0x2580', '0x259F'), // Block Elements
      array('0x25A0', '0x25FF'), // Geometric Shapes
      array('0x2600', '0x26FF'), // Miscellaneous Symbols
      array('0x2700', '0x27BF'), // Dingbats
      array('0x2800', '0x28FF'), // Braille Patterns
      array('0x2E80', '0x2EFF'), // CJK Radicals Supplement
      array('0x2F00', '0x2FDF'), // Kangxi Radicals
      array('0x2FF0', '0x2FFF'), // Ideographic Description Characters
      array('0x3000', '0x303F'), // CJK Symbols and Punctuation
      array('0x3040', '0x309F'), // Hiragana
      array('0x30A0', '0x30FF'), // Katakana
      array('0x3100', '0x312F'), // Bopomofo
      array('0x3130', '0x318F'), // Hangul Compatibility Jamo
      array('0x3190', '0x319F'), // Kanbun
      array('0x31A0', '0x31BF'), // Bopomofo Extended
      array('0x3200', '0x32FF'), // Enclosed CJK Letters and Months
      array('0x3300', '0x33FF'), // CJK Compatibility
      array('0x3400', '0x4DB5'), // CJK Unified Ideographs Extension A
      array('0x4E00', '0x9FFF'), // CJK Unified Ideographs
      array('0xA000', '0xA48F'), // Yi Syllables
      array('0xA490', '0xA4CF'), // Yi Radicals
      array('0xAC00', '0xD7A3'), // Hangul Syllables
      array('0xD800', '0xDB7F'), // High Surrogates
      array('0xDB80', '0xDBFF'), // High Private Use Surrogates
      array('0xDC00', '0xDFFF'), // Low Surrogates
      array('0xE000', '0xF8FF'), // Private Use
      array('0xF900', '0xFAFF'), // CJK Compatibility Ideographs
      array('0xFB00', '0xFB4F'), // Alphabetic Presentation Forms
      array('0xFB50', '0xFDFF'), // Arabic Presentation Forms-A
      array('0xFE20', '0xFE2F'), // Combining Half Marks
      array('0xFE30', '0xFE4F'), // CJK Compatibility Forms
      array('0xFE50', '0xFE6F'), // Small Form Variants
      array('0xFE70', '0xFEFE'), // Arabic Presentation Forms-B
      array('0xFEFF', '0xFEFF'), // Specials
      array('0xFF00', '0xFFEF'), // Halfwidth and Fullwidth Forms
      array('0xFFF0', '0xFFFD')  // Specials
    );
