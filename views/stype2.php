<?php if (isset($data['title'])): ?>
<h3><?php echo $data['title'] ?></h3>

<?php endif; ?>
<table class="t2">
  <colgroup>
    <col class="c1" span="1">
    <col class="c2" span="2">
  </colgroup>
  <thead>
    <tr>
      <th class="a" scope="col"><?php echo $question_number++ ?>. <?php echo $data['questions'][0] ?></th>
      <th class="b" scope="col">R</th>
      <th class="c" scope="col">%</th>
    </tr>
  </thead>
  <tbody>
<?php $index = count($data['summary']) - 1;
      $max = 0;
      foreach ($data['summary'] as $summary):
          $max = (($summary[0] > $max) ? $summary[0] : $max);
      endforeach; ?>
<?php foreach ($data['summary'] as $ks => $summary): ?>
    <tr<?php echo ($ks == $index) ? ' class="h"' : '' ?>>
      <th class="d" scope="row"><?php echo $data['answers'][$ks] ?></th>
<?php if ($summary[0] == $max):
          $tag1 = '<strong>';
          $tag2 = '</strong>';
      else:
          $tag1 = '';
          $tag2 = '';
      endif; ?>
      <td class="e"><?php echo $tag1, $summary[0], $tag2 ?></td>
      <td class="f"><?php echo $tag1, $summary[1], $tag2 ?></td>
    </tr>
<?php endforeach; ?>
    <tr class="g">
      <th scope="row">Total</th>
      <td><?php echo $response_count ?></td>
      <td>100</td>
    </tr>
  </tbody>
</table>

