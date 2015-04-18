<?php $colspan = count($data['answers']) + 1 ?>
<?php if (isset($data['title'])): ?>
<h3><?php echo $data['title'] ?></h3>

<?php endif; ?>
<table class="t1">
  <colgroup>
    <col class="c1" span="1">
    <col class="c2" span="<?php echo $colspan ?>">
    <col class="c3" span="<?php echo $colspan ?>">
  </colgroup>
  <thead>
    <tr>
      <th class="a" scope="col" rowspan="2"><?php echo (isset($data['help']) ? $data['help'] : '')?></th>
      <th class="b" scope="col" colspan="<?php echo $colspan ?>">Responses</th>
      <th class="c" scope="col" colspan="<?php echo $colspan ?>">Percentage</th>
    </tr>
    <tr>
<?php foreach ($data['answers'] as $k => $answer): ?>
<?php $class = '';
      if ($k == 0) $class = ' class="d"';
      if ($k == $colspan - 2) $class = ' class="e"'; ?>
      <th<?php echo $class ?> scope="col"><?php echo $answer ?></th>
<?php endforeach; ?>
      <th class="f" scope="col">Tot.</th>
<?php foreach ($data['answers'] as $k => $answer): ?>
<?php $class = '';
      if ($k == 0) $class = ' class="g"';
      if ($k == $colspan - 2) $class = ' class="h"'; ?>
      <th<?php echo $class ?> scope="col"><?php echo $answer ?></th>
<?php endforeach; ?>
      <th class="i" scope="col">Tot.</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($data['summary'] as $ks => $summary): ?>
    <tr>
      <th scope="row"><?php echo $question_number++ ?>. <?php echo $data['questions'][$ks] ?></th>
<?php $max1 = max(array_slice($summary, 0, $colspan - 1));
      $max2 = max(array_slice($summary, $colspan - 1, $colspan - 1)); ?>
<?php foreach ($summary as $ke => $element): ?>
<?php if (($ke <= $colspan - 2 && $element == $max1) || $ke > $colspan - 2 && $element == $max2):
          $tag1 = '<strong>';
          $tag2 = '</strong>';
      else:
          $tag1 = '';
          $tag2 = '';
      endif; ?>
<?php $class = '';
      if ($ke == 0) $class = ' class="j"'; //first response column data
      if ($ke == $colspan - 2) $class = ' class="k"'; // last response column data
      if ($ke == $colspan - 1) $class = ' class="m"'; // first percentage column data
      if ($ke == (($colspan - 1) * 2) - 1) $class = ' class="n"' //last percentage column data ?>
      <td<?php echo $class ?>><?php echo $tag1, $element, $tag2 ?></td>
<?php if ($ke == $colspan - 2): // response totals column data ?>
      <td class="l"><?php echo $response_count ?></td>
<?php endif; ?>
<?php endforeach; // percentage totals column data ?>
      <td class="o">100</td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>

