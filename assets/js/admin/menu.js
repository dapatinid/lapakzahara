function show_box_menu_admin() {
    box_menu_admin.style.display = 'block';
}
function toggleBoxMenuAdmin() {
        var boxMenuAdmin = document.getElementById("box_menu_admin");
        if (boxMenuAdmin.style.display === "block") {
            boxMenuAdmin.style.display = "none";
        } else {
            boxMenuAdmin.style.display = "block";
        }
    }