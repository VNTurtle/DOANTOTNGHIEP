$('.slick-slider').slick({
    dots: false,
    infinite: true,
    speed: 300,
    
    slidesToShow: 5,
    slidesToScroll: 1,
    prevArrow:'.btn-pre-slider',
    nextArrow:'.btn-next-slider',
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 2,
                infinite: true,
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