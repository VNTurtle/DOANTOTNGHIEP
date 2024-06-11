function ShowMenu(element){
    var target = element.dataset.target;
    var detail = document.getElementById(target);
  
    var codeElement = element.parentNode.querySelector('.category-code'); 
    console.log(codeElement);
    detail.classList.toggle("show");
    codeElement.classList.toggle("show");
}

