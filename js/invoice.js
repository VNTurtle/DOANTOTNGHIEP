function toggleInvoiceDetail(element) {

    var target = element.dataset.target;
    var detail = document.getElementById(target);
  
    var codeElement = element.querySelector('.invoice-code');
  
    detail.classList.toggle("show");
    codeElement.classList.toggle("show");
  }

  var tabLinks = document.querySelectorAll('.tab-link');
  var tabContents = document.querySelectorAll('.tab-content');
  
  // Lặp qua mỗi tab link
  tabLinks.forEach(function(tabLink, index) {
      // Gán sự kiện click cho mỗi tab link
      tabLink.addEventListener('click', function() {
          // Xóa lớp active khỏi tất cả các tab link và tab content
          tabLinks.forEach(function(link) {
              link.classList.remove('active');
          });
          tabContents.forEach(function(content) {
              content.classList.remove('active');
          });
          // Thêm lớp active cho tab link được chọn
          this.classList.add('active');
          // Hiển thị tab content tương ứng với tab link được chọn
          tabContents[index].classList.add('active');
      });
  });