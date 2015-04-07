<h1>Survey Plugin for Wolf CMS</h1>

<h2>Introduction</h2>
<p>If you're reading this, you must have already installed the plugin. Great! The plugin doesn't require a database nor does it have any settings. You're ready to go, just about!</p>

<h2>Files in the Package</h2>
<p>You should have received the system in a zip file containing the following files:</p>
<pre>
  \readme.md
  \documentation.htm
  \index.php
  \sample
  \Survey.php
  \SurveyController.php
  \views\index.php
  \views\sidebar.php
  \i18n\en-message.php
  \i18n\sp-message.php
</pre>
<p>Please move the sample file to the Wolf public directory. You may delete the readme.md file, if you wish.</p>
<p>Note that there may be other language files in the i18n directory. You only need the language files for the languages you intend to support. Deleting the ones you don't need will not affect the plugin's operation.

<h2>How to Use the Plugin</h2>
<p>After you create one or more survey definition files and place them in Wolf's public directory, use this code in any Wolf page,</p>
<pre>&lt;?php if (Plugin::isEnabled('survey')) survey_conduct('my_survey'); ?&gt;</pre>
<p>where 'my_survey' is the survey definition file you wish to use. The if statement is really optional but prevents errors if you disable the plugin while there are still pages that call it.</p>

<h2>Creating Your Own Surveys</h2>
The system "knows" about surveys because surveys definition files describe the surveys. These files are normal text files that are stored in the public/ directory. You can create and edit text files with a text editor like notepad.exe under Windows or TextEdit on a Mac. You can also use a word processor like MS-Word, but make sure in all cases to save files as plain text. Open the sample survey file, called "sample," and take a look at it. At least some of it will make sense at first glance.

<h3>Survey Files</h3>
<h4>Overview</h4>
<p>Survey files are made up of two or more sections, each section with a unique section header that names that names the section. Section headers are surrounded by square brackets (i.e. []). The first section <i>must</i> be called meta. Subsequent sections may be called anything, but their names must be unique. You can't have two sections with the same name. The system does not sort sections, so you don't have to name them sequentially. However, doing so makes it easier on the human eye, and that's why they are numbered sequentially in the sample survey file.</p>
<p>Each section has a variable number of lines, and each line contains a pair of items separated by a coma. We call the first item a "property," and the second item the "property's value." Take the first line in the meta section.</p>
<pre>name = "Sample Survey"</pre>
<p>Since this is inside the meta section, you could read it as "the meta-name is Sample Survey." In other words, the name of the survey itself is "Sample Survey."</p>
<p>There are a few rules. Section headers should not contain spaces or square brackets. Property names should not contain spaces or the equals sign. Property values that are text must be surrounded by double quotes, but numeric property values shouldn't be. And that's about it.

<h4>Sections</h4>
The first section, which must always be called meta, contains the following properties:
<pre>name = "Sample Survey"
hello = "Please answer the following questions."
goodbye = "Thank you for taking this survey!"</pre>

<p>The name property is the name of the survey, and will appear as the survey's title on the survey form. The instructions property is the text that introduces the survey and optionally instructs survey takers. The text appears below the survey title on the survey form.</p>

<p>The thanks property is the message thanking survey takers for taking the survey. It appears instead of the instructions on the survey form when the system confirms the survey submission.

<p>After that, notice there are a number of lines grouped under headings called section_1, section_2, section_3, etc., each heading surrounded by square brackets. These are section headings. Section headings don't have to be called section_1, section_2, section_3, etc. They don't even have to be sequential. They could be green, blue, and yellow, or George, Thomas, and David. They must, however, be unique. They must also not contain spaces, and they must be surrounded by square brackets.</p>

<h3>What are Sections?</h3>
<p>A section is a group of one or more survey questions of the same type. There are three types of survey questions, types 1 through 3.</p>

<pre>[section_1]
type = 1
title = "Preferences"
help = "About your preferences..."
questions[] = "What size Coke do you prefer?"
questions[] = "What size popcorn do you prefer?"
questions[] = "What size candy bar do you prefer?"
questions[] = "What size T-Shirt do you wear?"
answers[] = "Small"
answers[] = "Med."
answers[] = "Large"
</pre>

<p>The very first line within any section is the survey question type. In the case of Type 1 questions, there follows a list of questions. Each question must be assigned to the name <code>questions</code>, followed by a pair of empty square brackets, and followed by an equals sign.</p>

<p>A couple of technical notes. On the right side of the equals sign, numbers aren't surrounded by double quotes, but letters, words, and phrases are. If you're using Word, the double quotes cannot be curly quotes. They must be the standard straight quotes. Also, it may seem that the name <code>questions</code> is duplicated, but the pairs of empty quotes tell the system to insert a running tally number, so the result actually becomes:</p>

<pre>questions[0] = "What size Coke do you prefer?"
questions[1] = "What size popcorn do you prefer?"
questions[2] = "What size candy bar do you prefer?"
questions[3] = "What size T-Shirt do you wear?"</pre>

<p>And so on and so forth if there were more questions.</p>

<p>After the list of questions, there is a list of possible answers. The possible answers should apply to all the questions. When processing this section, the system will place each question, followed by three round checkboxes, on its own line. The three round checkboxes will be aligned under the headings Small, Medium, and Large.</p>

<h4>Type 2</h4>

<p>This type of question allows one and only one multiple choice question.</p>

<pre>[section_4]
type = 2
questions[] = "What kind of car do you drive?"
answers[] = "Honda"
answers[] = "Toyota"
answers[] = "Ford"
answers[] = "General Motors"
answers[] = "Other"</pre>
answers[] = "I don't drive"

<p>When processing this section, the system will display the question on one line, and beneath it, the list of questions, each on its own line, and each starting with a round checkbox to select it. A note on good surveying practice: you should always include a "none of the above" answer.</p>

<h4>Type 3</h4>

<p>This is the last survey question type. Like Type 2, it allows only one multiple choice question, except people can check all the answers that apply.</p>

<pre>[section_6]
type = 3
questions[] = "Things you like about your job"
answers[] = "Short commute"
answers[] = "Good supervisor"
answers[] = "Good co-workers"
answers[] = "Fulfilling"
answers[] = "High status"
answers[] = "Fun environment"
answers[] = "Pays well"
answers[] = "I don't like my job"</pre>

<p>When processing this section, the system will display the question followed by the list of answers, expect instead of round checkboxes, there will be square checkboxes. The fact that they are square indicates that more than one can be selected at the same time.</p>

<p>A round checkbox is actually called a "radio button," after the row of buttons on car radios that let you select different preset stations. Only one radio button can be pushed at once. If you press a different radio button, the radio button previously pushed in pops out. The square checkbox is called a checkbox, after the checkboxes found on paper forms.</p>

<h2>Taking Your Survey</h2>

<p>To take your survey, go to the web page where you entered the PHP code that calls the survey. The system will display your survey on the screen and allow you to fill it. When done, press the Done! button at the bottom of the survey.</p>

<p>The system will not allow you to submit an incomplete survey. After you submmit a completed survey, the system shows you a confirmation page with the thank-you message you selected. At the bottom of the page there will be a Validation Timestamp. Although the surveys are anonymous, the validation timestamp can be used to identify an entry in the survey response file, and to double check that the answers given are the same.</p>
