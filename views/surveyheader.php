<div id="surveyform">


<h2><?php echo $survey_name ?></h2>

<p><?php echo $user_msg ?></p>

<?php if ($error_question): ?>
<p><?php echo $error_msg ?></p>

<?php endif; ?>
<form id="form" name="form" method="post">

