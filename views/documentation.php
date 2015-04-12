<h1>Survey Plugin for Wolf CMS</h1>

<h2>Introduction</h2>

<p>If you're reading this, you must have already installed the plugin. Great!</p>

<h2>Files in the Package</h2>

<p>You should have received the plugin in a zip file containing the following files:</p>

<pre>
	\readme.md
	\documentation.htm
	\index.php
	\Survey.php
	\SurveyController.php
	\views\index.php
	\views\sidebar.php
	\i18n\en-message.php
	\i18n\sp-message.php
	\misc\sample-survey
	\misc\survey.css
	\misc\summary.css
</pre>

<p>To replace missing files, you may download the latest version from <a href="https://github.com/RobertHallsey/Survey-plugin-for-Wolf-CMS">the plugin's GitHub repository.</a></p>

<p>Please move the sample-survey file to the Wolf public directory. You may delete the readme.md file, if you wish.</p>

<p>Note that there may be other language files in the i18n directory. You only need the language files for the languages you intend to support. Deleting the ones you don't need will not affect the plugin's operation.</p>

<h2>How to Use the Plugin</h2>

<p>After you create one or more survey definition files and place them in Wolf's public directory, use this code in any Wolf page,</p>

<pre>&lt;?php if (Plugin::isEnabled('survey')) survey_conduct('my_survey'); ?&gt;</pre>

<p>where 'my_survey' is the survey definition file you wish to use. The if statement is optional but prevents errors if you disable the plugin while there are still pages that call it.</p>

<p>To display a summary of responses to a particular survey, use this code in any Wolf page,</p>

<pre>&lt;?php if (Plugin::isEnabled('survey')) survey_summarize('my_survey'); ?&gt;</pre>

<h2>Creating Your Own Surveys</h2>

<p>The plugin "knows" about surveys because surveys definition files describe the surveys. These files are normal text files that are stored in Wolf's public directory. You can create and edit text files with a text editor like notepad.exe under Windows or TextEdit on a Mac. You can also use a word processor like MS-Word, but make sure in all cases to save files as plain text. Open the sample survey file, called "sample-survey," and take a look at it. You may recognize the familiar INI format, but even if not, at least some of it will make sense at first glance.</p>

<h3>Survey Definition Files</h3>

<p>Survey definition files are made up of two or more sections, each section with a unique section heading that names the section. Section headings are surrounded by square brackets (i.e. []). The first section, which must be called "meta," defines the survey itself, and subsequent sections define the questions the survey will ask. Those subsequent sections may be called anything, but their names must be unique. You can't have two sections with the same name. Section headings must also not contain spaces. The plugin does not sort sections, so you don't have to name them sequentially. However, doing so makes it easier on the human eye, and that's why they are numbered sequentially in the sample survey file.</p>

<p>Each section has a variable number of lines, and each line contains a pair of items separated by an equals sign. We call the first item a "property," and the second item the "property's value." Take the first line in the meta section. If you want to get technical, it says that the property value of the property "name" is "Sample Survey." You could also just say, "The name of the survey is 'Sample Survey.'"</p>

<pre>name = "Sample Survey"</pre>

<p>Property-value pairs have a couple of rules. Only certain property names are recognized. Property values that are text must be surrounded by double quotes, but numeric property values should not be. Property names that repeat must end in an empty pair of square brackets ([]).</p>

<p>Let's look at the "meta" section. It contains the following properties:</p>

<pre>name = "Sample Survey"
hello = "Please answer the following questions."
goodbye = "Thank you for taking this survey!"</pre>

<p>The name property is the name of the survey, and will appear as the survey's title on the survey form. The hello property is the text that introduces the survey and optionally instructs survey takers. The text appears below the survey title on the survey form. The goodbye property is the message that appears instead of the hello message after the survey form is submitted.</p>

<p>Now let's look at the other sections, the ones that define survey questions. As mentioned earlier, their heading names can be anything, as long as they're unique (and not "meta"). There are three types of survey questions, types 1, 2, and 3.</p>

<h4>Type 1 Question</h4>

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

<p>The very first line within any section is the survey question type. In the case of Type 1 questions, there follows a list of questions. Each question must be assigned to the property name "questions," and because there are more than one question, the property name is followed by a pair of empty square brackets.</p>

<p>Recall that, in the case of property values, text is surrounded by double quotes but numbers are not. If you're using Word, the double quotes cannot be curly quotes. They must be the standard straight quotes. Also, it may seem that the name <code>questions</code> is duplicated, but the pairs of empty quotes tell the plugin to insert a running tally number, so the result becomes:</p>

<pre>questions[0] = "What size Coke do you prefer?"
questions[1] = "What size popcorn do you prefer?"
questions[2] = "What size candy bar do you prefer?"
questions[3] = "What size T-Shirt do you wear?"</pre>

<p>And so on and so forth if there are more questions.</p>

<p>After the list of questions, there is a list of possible answers. The possible answers should apply to all the questions. When processing this section, the plugin places each question, followed by three round checkboxes, on its own line. The three round checkboxes appear under the headings Small, Medium, and Large.</p>

<p>The help property is a message the plugin displays at the top of the questions, to the left of the answer headings. The help property is optional, and only type 1 questions recognize this property.</p>

<p>The title property is also optional, but can be used in all three question types. It's a heading that appears before the question. It's optional so that you can group several questions under the same title.</p>

<h4>Type 2 Question</h4>

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

<p>When processing this section, the plugin displays the question on one line, and beneath it, the list of questions, each on its own line, and each starting with a round checkbox to select it. A note on good surveying practice: you should always include a "none of the above" answer.</p>

<h4>Type 3 Question</h4>

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

<p>When processing this section, the plugin will display the question followed by the list of answers, expect instead of round checkboxes, there will be square checkboxes. The fact that they are square indicates that more than one can be selected at the same time.</p>

<p>A round checkbox is actually called a "radio button," after the row of buttons on car radios that let you select different preset stations. Only one radio button can be pushed at once. If you press a different radio button, the radio button previously pushed in pops out. The square checkbox is called a checkbox, after the checkboxes found on paper forms.</p>

<h2>Taking Your Survey</h2>

<p>To take your survey, view the Wolf page where you entered the PHP code that calls the survey. The plugin displays your survey on the screen and allow you to fill it. When done, press the Done! button at the bottom of the survey.</p>

<p>The plugin will not allow you to submit an incomplete survey. After you submmit a completed survey, the plugin shows you a confirmation page with the thank-you message you selected. At the bottom of the page there is a Validation Timestamp. Although the surveys are anonymous, the validation timestamp can be used to identify an entry in the survey response file, and to double check that the answers given are the same.</p>

<h2>Survey Summaries</h2>

<p>To view a summary of the responses to your survey, view the Wolf page where you entered the PHP code that calls the survey summary function. Summaries are not stored but calculated, so the results will always include all responses entered.</p>

<h2>Styling Your Survey Forms and Summaries</h2>

<p>The plugin generates the survey forms and summary charts without any styling. Here is probably the minimum styling you would want to apply. </p>

<h3>surveyform.css</h3>

<p>Here, table borders are collapsed. Tables are used for Type 1 questions, and collapsing their borders means that there is no space between the right border of one cell and the left border of the next cell. Table data, the cells themselves, are set to centered alignment. This will center the radio buttons under their headings. However, the first cell in a row is the question help or the survey question, so it should be left-aligned.</p>

<pre>&lt;style>
  table {
    border-collapse:collapse;
  }
  td {
    text-align: center;
  }
  th:first-child,
  td:first-child {
    text-align: left;
  }
&lt;/style></pre>

<h3>summarychart.css</h3>

<p>The summary chart all tables. Here, since most cells contain numbers, the cells are right-aligned. However, as in the case of the summary form, the first cell in a row is the question or the answer option, so it is left-aligned.</p>

<pre>&lt;style>
  td {
    text-align: right;
  }
  th:first-child,
  td:first-child {
    text-align: left;
  }
&lt;/style></pre>

<p>There are several ways you can implement styles. The easiest way is to simply place your &lt;style> section in your Wolf page, just before the call to the plugin itself. However, you should know that's poor practice. The code doesn't validate because the &lt;style> tag isn't supposed to go inside the &lt;body> tag.</p>

<p>Another way is to incorporate your survey form and chart styling into your general Wolf page layout. You can also have a layout specifically for pages with survey forms and a layout specifically for pages with survey charts Finally, you can have one main layout and use the page metadata to determine if the survey form or survey chart CSS files should be linked in the &lt;head> section. For your convenience, the misc folder has two CSS files you may use.</p>

<p>To help you build a killer CSS file that styles your survey forms and charts exactly the way you want, here's the HTML skeleton for each. Both survey form and survey summary are surrounded by a div with an id attribute. This lets you target your CSS specifically to the elements within. The ids are "sf:" for the survey form and "ss:" for the survey summary. Colons in ids are legal but not frequently used, so they shouldn't clash with ids you've used in your layouts.</p>

<h3>Survey Form HTML Skeleton</h3>

<pre>&lt;div id="sf:">&lt;!-- surveyform -->
&lt;form id="form" name="form" method="post">

&lt;-- Type 1 Questions --&gt;
&lt;h3>Title Property&lt;/h3>
&lt;table>
  &lt;thead>
    &lt;tr>
      &lt;th>&lt;!-- empty or displays help property -->&lt;/th>
      &lt;th>&lt;!-- repeats for each answer option -->&lt;/th>
   &lt;/tr>
  &lt;/thead>
  &lt;tbody>
    &lt;tr>
      &lt;td>#. Text of question&lt;/td>
      &lt;!-- next line repeats for each answer option -->
      &lt;td>&lt;input type="radio">&lt;/td>
    &lt;/tr>
  &lt;/tbody>
&lt;/table>

&lt;-- Type 2 Questions --&gt;
&lt;h3>Title Property&lt;/h3>
&lt;fieldset>
  &lt;legend>#. Text of question&lt;/legend>&lt;br>
  &lt;!-- next two lines repeat for each answer option -->
  &lt;input type="radio" id="Q50">
  &lt;label for="Q50">Label&lt;/label>&lt;br>
&lt;/fieldset>

&lt;-- Type 3 Questions --&gt;
&lt;fieldset>
  &lt;legend>#. Text of question&lt;/legend>&lt;br>
  &lt;!-- next two lines repeat for each answer option -->
  &lt;input type="checkbox" id="Q60">
  &lt;label for="Q60">Label&lt;/label>&lt;br>
&lt;/fieldset>

&lt;p>input type="reset">&lt;input type="submit">&lt;/p>
&lt;/form>
&lt;/div>&lt;!-- sf:surveyform --></pre>

<h3>Survey Chart HTML Skeleton</h3>

<p>And likewise, here is the HTML skeleton for the Survey Chart.</p>

<pre>&lt;div id="ss:">&lt;!-- surveysummary -->
&lt;h2>Name: Sample Survey&lt;/h2>

&lt;h3>Total Responses: n&lt;/h3>

&lt;h3>Title Property&lt;/h3>

&lt;-- Type 1 Questions --&gt;
&lt;table>
  &lt;colgroup>
    &lt;col span="1">
    &lt;col span="n">
  &lt;/colgroup>
  &lt;thead>
    &lt;tr>
      &lt;th>&lt;!-- empty or displays help property -->&lt;/th>
      &lt;th>Responses&lt;/th>
      &lt;th>Percentage&lt;/th>
    &lt;/tr>
    &lt;tr>
      &lt;th>&lt;!-- repeats for each answer option -->&lt;/th>
    &lt;/tr>
  &lt;/thead>
  &lt;tbody>
    &lt;!-- following table row repeats for each question -->
    &lt;tr>
      &lt;td>#. Text of question&lt;/td>
      &lt;!-- next line repeats for each answer option -->
      &lt;td>&lt;!-- &lt;strong>&lt;/strong> if response is the highest -->&lt;/td>
    &lt;/tr>
  &lt;/tbody>
&lt;/table>

&lt;-- Type 2 Questions --&gt;
&lt;h3>Title Property&lt;/h3>
&lt;table>
  &lt;colgroup>
    &lt;col span="1">
    &lt;col span="2">
  &lt;/colgroup>
  &lt;thead>
    &lt;tr>
      &lt;th>#. Text of question&lt;/th>
      &lt;th>R&lt;/th>
      &lt;th>%&lt;/th>
    &lt;/tr>
  &lt;/thead>
  &lt;tbody>
    &lt;!-- following table row repeats for each answer option -->
    &lt;tr>
      &lt;td>&lt;!-- answer option -->&lt;/td>
      &lt;td>&lt;!-- response count -->&lt;/td>
      &lt;td>&lt;!-- percent of response -->&lt;/td>
    &lt;/tr>
  &lt;/tbody>
&lt;/table>

&lt;-- Type 3 Questions --&gt;
&lt;table>
  &lt;colgroup>
    &lt;col span="1">
    &lt;col span="2">
  &lt;/colgroup>
  &lt;thead>
    &lt;tr>
      &lt;th>#. Text of question&lt;/th>
      &lt;th>R&lt;/th>
      &lt;th>%&lt;/th>
    &lt;/tr>
  &lt;/thead>
  &lt;tbody>
    &lt;!-- following table row repeats for each answer option -->
    &lt;tr>
      &lt;td>&lt;!-- answer option -->&lt;/td>
      &lt;td>&lt;!-- response count -->&lt;/td>
      &lt;td>&lt;!-- percent of response -->&lt;/td>
    &lt;/tr>
  &lt;/tbody>
&lt;/table>

&lt;p>End of Summary&lt;/p>

&lt;/div>&lt;!-- ss:surveysummary --></pre>
