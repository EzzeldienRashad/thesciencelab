<?php
//httpallow!
session_start();
if ((isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) || (isset($_POST["password"]) && password_verify($_POST["password"], '$2y$10$PWZp0FxpLcwgFDTE4SsBFOfif8K675xMibtCZ/I8VTmmNbdbNLl9q'))) {
    $_SESSION["isAdmin"] = true;
    echo "admin";
}
?>