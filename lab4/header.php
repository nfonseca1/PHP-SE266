<?php
include 'database.php';

?>

<section>
    <form method="get" action="./index.php">
        <label for="col">Sort Column: </label>
        <select name="col" id="col">
            <option value="id">id</option>
            <option value="corp">corp</option>
            <option value="incorp_dt">incorp_dt</option>
            <option value="email">email</option>
            <option value="zipcode">zipcode</option>
            <option value="owner">owner</option>
            <option value="phone">phone</option>
        </select>
        <label for="asc">Ascending: </label>
        <input type="radio" name="dir" value="ASC" id="asc" checked="checked">
        <label for="desc">Descending: </label>
        <input type="radio" name="dir" value="DESC" id="desc">
        <input type="hidden" name="action1" value="sort">
        <input type="submit">
        <input type="submit" name="action1" value="Reset">
    </form>
</section>
<section>
    <form method="get" action="./index.php">
        <label for="col2">Search Column: </label>
        <select name="col2" id="col">
            <option value="id">id</option>
            <option value="corp">corp</option>
            <option value="incorp_dt">incorp_dt</option>
            <option value="email">email</option>
            <option value="zipcode">zipcode</option>
            <option value="owner">owner</option>
            <option value="phone">phone</option>
        </select>
        <label for="term">Term: </label>
        <input type="text" name="term" id="term">
        <input type="hidden" name="action2" value="search">
        <input type="submit">
        <input type="submit" name="action2" value="Reset">
    </form>
</section>