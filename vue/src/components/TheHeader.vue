<template>
  <header class="navbar">
    <div class="navbar-container">
      <div class="navbar-logo">
        <a href="#">家づくりスタート<span>BOOK</span></a>Ver.2.0
      </div>
          <!-- <nav class="navbar-links" :class="{ 'is-active': isMenuOpen }">
            <a href="#home" @click="closeMenu">Home</a>
            <a href="#about" @click="closeMenu">About</a>
            <a href="#services" @click="closeMenu">Services</a>
            <a href="#contact" @click="closeMenu">Contact</a>
          </nav>

          <button 
            class="navbar-toggle" 
            @click="toggleMenu" 
            :aria-expanded="isMenuOpen"
            aria-label="Toggle navigation"
          >
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
          </button> -->
    </div>
    <!-- <section class="sub-header">
        <div class="sub-header-container">
          <h1>家づくりスタートBOOK <span>回答内容確認シート</span></h1>
        </div>
    </section> -->
  </header>
  
</template>

<script setup>
import { ref } from 'vue'

// State for mobile menu toggle
const isMenuOpen = ref(false)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const closeMenu = () => {
  isMenuOpen.value = false
}
</script>

<style scoped>
/* Navbar Base Styles */

</style>