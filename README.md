# standardnotes_day_one_importer
Day One JSON Export Importer for Standard Notes


This is a very simple PHP script that just transforms the JSON Export from Day One (https://help.dayoneapp.com/en/articles/440668-exporting-entries) to the plaintext import format from Standard Notes (https://dashboard.standardnotes.org/tools)

Tags are not yet supported. However, if you want to set tags you can do so manually.

## Workflow
- Place .php file in the same directory as the .json files from the Day One Archive
- create empty dir "imports" in same directory
- Run php file like so: $ php import.php
=> The ./imports directory will get populated with new .json files. These files contain the Journal name as the filename and a number at the end which shows how many posts are in the file.

This effectively helps you to validate if all entries are correctly imported. Make sure to compare before and after amounts of your notes and then calculate if the difference is the expected amount.
Sometimes UUIDs collide and imports are skipped. Since the are randomly generated you can just delete all recently imported notes from the last batch and restart the run. Collisions are rare however.

