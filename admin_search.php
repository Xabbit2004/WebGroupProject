<?php
if(isset($_POST['searchButton'])){
    $searchText = htmlspecialchars($_POST['searchText']);
    if(isset($_POST['search_type']) == 'byTitle'){
        $all = "SELECT ISBN,TITLE,AUTHOR,PUBDATE, RATING,STATUS,EMAIL,CHECKDATE, EXPDATE FROM BOOKS
        WHERE TITLE LIKE '%$searchText%'";
        $_SESSION['search_form'] = $all;
        exit();
    }

}
else{
    $all = " ";
    $_SESSION['search_form'] = $all;
    exit();
}

header("Location: admin_page.php");
exit();
?>