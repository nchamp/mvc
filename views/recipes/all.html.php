<?php
// available vars:
// $id
// $recipe
?>
<html>
<body>
  <?php foreach($template->recipes as $recipe): ?>
    <li><a href = "/wda-assign-2/recipes/<?php echo $recipe->RecipeID; ?>"><?php echo $recipe->Ingredients; ?></a></li>
  <?php endforeach; ?>
</body>
</html>