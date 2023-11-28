<form method = "post">
<input type = "hidden" name = "post_id" value="1">
<input type = "submit" name="submit">
</form>

<form method = "post">
<input type = "hidden" name = "post_id" value="2">
<input type = "submit" name="submit">
</form>

<?php
    if(isset($_POST["submit"])){
        echo $_POST["post_id"];
    }
?>