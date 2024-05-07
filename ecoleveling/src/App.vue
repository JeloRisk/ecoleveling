<template>
  <nav
    ref="elementToMeasure"
    class="flex items-center justify-between px-4 py-4 lg:px-32 lg:py-6 bg-gray-800 text-white sticky top-0 z-10"
  >
    <div class="flex items-center">
      <img
        src=".\assets\logo-2.svg"
        alt="Logo"
        class="h-8 w-8 mr-2"
      >
      <span class="text-lg font-bold"> Ecoleveling</span>
    </div>
    <div class="lg:hidden">
      <button
        @click="toggleMenu"
        class="text-white focus:outline-none focus:bg-gray-600"
      >
        <svg
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            :class="{ 'hidden': isMenuOpen }"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16m-7 6h7"
          ></path>
          <path
            :class="{ 'hidden': !isMenuOpen }"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          ></path>
        </svg>
      </button>
    </div>

    <div
      :class="{ 'hidden': !isMenuOpen }"
      class="lg:flex sm:hidden"
    >
      <RouterLink
        to="/"
        class="mx-4"
      >Dashboard</RouterLink>
      <RouterLink
        to="/rooms"
        class="mx-4"
      >Rooms</RouterLink>
      <RouterLink
        to="/activity-log"
        class="mx-4"
      >Activity Log</RouterLink>
    </div>
  </nav>
  <div
    v-if="isMenuOpen"
    @click="toggleMenu"
    :style="{ marginTop: `${navHeight}px` }"
    class="fixed top-0 right-0 bottom-0 left-0 bg-opacity-50 z-50 flex justify-end p-4"
  >
    <div class="bg-gray-800  w-40 shadow-lg  rounded-lg h-fit  ">
      <RouterLink
        to="/"
        class="block p-4 hover:bg-gray-300 hover:text-gray-800 text-white rounded-lg"
      >Dashboard</RouterLink>
      <RouterLink
        to="/rooms"
        class="block p-4 hover:bg-gray-300 hover:text-gray-800 text-white rounded-lg"
      >Rooms</RouterLink>
      <RouterLink
        to="/activity-log"
        class="block p-4 hover:bg-gray-300 hover:text-gray-800 text-white rounded-lg"
      >Activity Log</RouterLink>
    </div>
  </div>


  <main class="bg-[#F5F5F5] lg:px-40 pt-8 p-4">
    <RouterView />
  </main>

</template>

<script>
  import { RouterLink, RouterView } from 'vue-router';

  export default {
    components: {
      RouterLink,
      RouterView
    },
    data() {
      return {
        isMenuOpen: false,
        navHeight: null,
        isSmallScreen: false
      };
    },
    mounted() {
      this.getHeight();
      this.checkScreenSize();
      window.addEventListener('resize', this.checkScreenSize);
    },
    beforeUnmount() {
      window.removeEventListener('resize', this.checkScreenSize);
    },
    methods: {
      getHeight() {
        this.navHeight = this.$refs.elementToMeasure.clientHeight;
      },
      checkScreenSize() {
        this.isSmallScreen = window.innerWidth <= 640;
        if (!this.isSmallScreen) {
          this.closeMenu();
        }
      },
      toggleMenu() {
        this.isMenuOpen = !this.isMenuOpen;
      },
      closeMenu() {
        this.isMenuOpen = false;
      }
    }
  };
</script>
