<?php
// available vars:
// $id
// $recipe
?>
<html>
<body>
  <?php if (count($template->recipes)): ?>
  	<?php print_r($template->recipes); ?>
  <?php else: ?>
    <h2>No search results found!</h2>
  <?php endif; ?>
</body>
</html>
