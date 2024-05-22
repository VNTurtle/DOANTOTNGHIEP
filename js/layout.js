function toggeleMenu(){
    var menuBar=document.querySelector('.opacity-menu');
    var headerNav=document.querySelector('.header-nav');
    // Thêm class mới vào menuBar và headerNav
    menuBar.classList.toggle('current');
    headerNav.classList.toggle('current')
}

function CLoseMenu(){
    var menuBar=document.querySelector('.opacity-menu');
    var headerNav=document.querySelector('.header-nav');

    // Xóa class curent 
    menuBar.classList.remove('current');
    headerNav.classList.remove('current');
}