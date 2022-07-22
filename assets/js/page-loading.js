$(window).on("load", async () => {
    const preloader = $('.page-loading');
    preloader.remove('active');
    setTimeout(() => {
        preloader.remove();
    }, 2000);
})