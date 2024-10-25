<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-b from-green-100 to-white">
    <Navbar />

    <main class="flex-grow container mx-auto px-4 py-8 sm:py-12 lg:py-16 xl:py-24">
      <section class="text-center mb-12 sm:mb-16 lg:mb-24">
        <h1 class="text-4xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-800 mb-4 lg:mb-6">
          Simplify Your Document Management
        </h1>
        <p class="text-lg sm:text-xl lg:text-xl text-gray-600 mb-8 lg:mb-12 max-w-4xl mx-auto">
          Organize, collaborate, and secure your documents with ease.
        </p>
        <router-link to="/register">
          <button
            class="bg-green-600 text-white px-6 py-3 sm:px-6 sm:py-3 rounded-full font-semibold hover:bg-green-700 transition-colors"
          >
            Register Now
          </button>
        </router-link>
      </section>

      <section id="features" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16 lg:mb-24">
        <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md">
          <h2 class="text-xl sm:text-2xl text-green-500 font-semibold mb-2 sm:mb-4">
            Centralized S3 Storage
          </h2>
          <p class="text-gray-600 text-base sm:text-lg">
            Access, manage, and securely store all your documents with the power of S3 technology, ensuring easy
            retrieval, seamless collaboration, and scalability.
          </p>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md">
          <h2 class="text-xl sm:text-2xl text-green-500 font-semibold mb-2 sm:mb-4">
            Team Collaboration
          </h2>
          <p class="text-gray-600 text-base sm:text-lg">
            Work together effortlessly with our upcoming collaboration features, <strong>Launching soon!</strong>
          </p>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md">
          <h2 class="text-xl sm:text-2xl text-green-500 font-semibold mb-2 sm:mb-4">
            Advanced Security
          </h2>
          <p class="text-gray-600 text-base sm:text-lg">
            Protect your data with robust encryption and secure S3 storage, ensuring your documents are safe and
            accessible only to authorized users.
          </p>
        </div>
      </section>

      <section id="contact" class="text-center max-w-2xl mx-auto">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-8 lg:mb-12">Get in Touch</h2>
        <form @submit.prevent="submitForm" class="space-y-4 sm:space-y-6">
          <div>
            <label for="name" class="sr-only">Your Name</label>
            <input
              id="name"
              type="text"
              placeholder="Your Name"
              v-model="name"
              class="w-full px-4 py-2 sm:py-3 border rounded text-base sm:text-lg"
              required
            />
          </div>
          <div>
            <label for="email" class="sr-only">Your Email</label>
            <input
              id="email"
              type="email"
              placeholder="Your Email"
              v-model="email"
              class="w-full px-4 py-2 sm:py-3 border rounded text-base sm:text-lg"
              required
            />
          </div>
          <div>
            <label for="message" class="sr-only">Your Message</label>
            <textarea
              id="message"
              placeholder="Your Message"
              v-model="message"
              class="w-full px-4 py-2 sm:py-3 border rounded text-base sm:text-lg"
              rows="4"
              required
            ></textarea>
          </div>
          <button
            type="submit"
            class="w-full bg-green-600 text-white px-4 py-3 sm:py-2 rounded-full font-semibold hover:bg-green-700 transition-colors"
          >
            Send Message
          </button>
        </form>
      </section>
    </main>

    <footer class="bg-green-600 text-white py-3 sm:py-3">
      <div class="container mx-auto px-4 text-center">
        <p class="text-base sm:text-md">&copy; 2024 DocManage. All rights reserved for Narwey.</p>
      </div>
    </footer>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import Navbar from "./Nav.vue";

const WEB3FORMS_ACCESS_KEY = "7ef80177-acd6-4b6b-83c4-bef49cbe4b59"; // Replace with your Web3Forms access key

export default {
  components: {
    Navbar,
  },
  data() {
    return {
      name: "",
      email: "",
      message: "",
    };
  },
  methods: {
    async submitForm() {
      try {
        
        const response = await fetch("https://api.web3forms.com/submit", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify({
            access_key: WEB3FORMS_ACCESS_KEY,
            name: this.name,
            email: this.email,
            message: this.message,
          }),
        });

        const result = await response.json();
        if (result.success) {
          Swal.fire({
            icon: "success",
            title: "Message Sent!",
            text: "Your message has been sent successfully.",
            confirmButtonText: "OK",
          });

          this.resetForm();
        } else {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Failed to send message. Please try again.",
          });
        }
      } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Failed to send message. Please try again.",
          });
      }
    },
    resetForm() {
      this.name = "";
      this.email = "";
      this.message = "";
    },
  },
};
</script>