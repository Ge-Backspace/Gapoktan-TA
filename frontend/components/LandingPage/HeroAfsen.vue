<template>
  <div>
    <!-- Hero Section Begin -->
    <section class="hero-section">
      <client-only>
      <carousel class="hero-items" :items="1" :nav="false">
        <div
          class="single-hero-items set-bg bg-image1"
          
        >
          <div class="container">
            <div class="row">
              <div class="col-lg-5">
                <span>Bag,kids</span>
                <h1>Black friday</h1>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore
                </p>
                <a href="#" class="primary-btn">Shop Now</a>
              </div>
            </div>
          </div>
        </div>
        <div
          class="single-hero-items bg-image2"
        >
          <div class="container">
            <div class="row">
              <div class="col-lg-5">
                <span>Bag,kids</span>
                <h1>Black friday</h1>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore
                </p>
                <a href="#" class="primary-btn">Shop Now</a>
              </div>
            </div>
          </div>
        </div>
        <div
          class="single-hero-items bg-image3"
        >
          <div class="container">
            <div class="row">
              <div class="col-lg-5">
                <span>Bag,kids</span>
                <h1>Black friday</h1>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore
                </p>
                <a href="#" class="primary-btn">Shop Now</a>
              </div>
            </div>
          </div>
        </div>
      </carousel>
      </client-only>
    </section>
    <!-- Hero Section End -->
  </div>
</template>

<script>

// import carousel from "vue-owl-carousel";

export default {
  name: "HeroShayna",
  // components: {
  //   carousel,
  // },
};
</script>

<style scoped>
  .bg-image1 {
    background-image: url('../../assets/img/LandingPage/hero-1.jpg');
  }
  .bg-image2 {
    background-image: url('../../assets/img/LandingPage/hero-2.jpg');
  }
  .bg-image3 {
    background-image: url('../../assets/img/LandingPage/hero-3.jpg');
  }
</style>