Survey Plugin for Wolf CMS

This plugin allows you to conduct surveys and summarize the responses in your Wolf CMS pages. It requires Wolf CMS version 0.7.7 or later. The plugin is an interface between Survey Generator and Wolf CMS. It provides two principal functions, one that conducts surveys, and the other that summarizes their responses. How to create surveys is explained in the included Surgey Generator documentation.

You should have received these files in the zip archive:
readme.md - Readme (this file)
index.php - Survey Generator interface (Wolf CMS front end)
SurveyController.php - Survey Controller (Wolf CMS back end)
Survey.php - Survey Generator class file
documentation.htm - Survery Generator documentation
survey.css - Optional CSS code
sample-survey - sample definition file
sample-survey.cvs - sample results files
views (folder with 10 files)
i18n (folder with 3 files)
icons (folder with 7 files)

To install the plugin, place all the files and directories from the zip in a directory called "survey" in your Wolf CMS plugins directory. Move the two "sample-survey" files to the Wolf CMS public directory. Go to the Wolf CMS administration panel and enable the plugin. And that's it!

To use the plugin in your Wolf CMS pages, use these functions in your PHP code. To present a survey form and process it upon submission, use survey_conduct('survey_definition_file'). To summarize the responses to a survey, use survey_summarize('survey_definition_file'). The survey definition file argument can get given as an absolute path or a relative one. Relative paths are relative to the Wolf CMS public directory.

To learn about creating and styling your own survey forms and summaries, please see the Survey Generator's documentation.

This work is copyright 2015 Robert Hallsey, rhallsey@yahoo.com.

Icons provided free by Victor Petrovich Ivlichev <http://www.aha-soft.com>.

This work is released under the GNU GPL 3.0 license.
For more info, see https://www.gnu.org/copyleft/gpl.html

