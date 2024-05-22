$('.slider-for').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-nav'
});
$('.slider-nav').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: '.slider-for',
    dots: false,
    arrows: false,
    centerMode: true,
    centerPadding: '60px',
    focusOnSelect: true,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: false
            }
        },
        {
            breakpoint: 600,
            settings: {
                arrows: false,
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                arrows: false,
                slidesToShow: 2,
                slidesToScroll: 1
            }
        }
    ]
});


$('.slick-slider2').slick({
    dots: false,
    infinite: true,
    speed: 300,
    
    slidesToShow: 5,
    slidesToScroll: 1,
    prevArrow:'.btn-pre-slider1',
    nextArrow:'.btn-next-slider1',
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: false
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        }
    ]
});

$('.slick-slider3').slick({
    dots: false,
    infinite: true,
    speed: 300,
    
    slidesToShow: 5,
    slidesToScroll: 1,
    prevArrow:'.btn-pre-slider2',
    nextArrow:'.btn-next-slider2',
    responsive: [
        {
            breakpoint: 1025,
            settings: {
                slidesToShow: 4,
                slidesToScroll: 3,
                infinite: true,
                dots: false
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        }
    ]
});

// Lấy tất cả các liên kết tab và nội dung tab
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
