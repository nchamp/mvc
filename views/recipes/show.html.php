<?php
// available vars:
// $id
// $recipe
?>
<html>
<body>
  <?php if (isset($template->recipe)): ?>
    <h2>This is the show page for the recipe with ingredients: "<?php echo $template->recipe->Ingredients; ?>" and the id "<?php echo $template->recipe->RecipeID; ?>"</h2>
  <?php else: ?>
    <h2>No recipe found!</h2>
  <?php endif; ?>
</body>
</html>
