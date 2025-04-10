<template>

  <form @submit.prevent="login" class="login-form mt-6">

    <p class="text-red-500"> {{ errorMessage }}</p>
    <p class="text-green-500" v-show="showStateMessage">{{ $t('auth.loginForm.stateMessage') }}</p>


    <div class="form-group mt-4">

      <label for="email" class="block text-gray-700">{{ $t('auth.loginForm.email') }}</label>
      <input type="email" v-model="email" :placeholder="$t('auth.loginForm.email')" id="email" required
             class="form-input mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"/>

    </div>

    <div class="form-group mt-4">

      <label for="password" class="block text-gray-700">{{ $t('auth.loginForm.password') }}</label>
      <input type="password" v-model="password" :placeholder="$t('auth.loginForm.password')" id="password" required
             class="form-input mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"/>

    </div>

    <button type="submit"
            :disabled="showStateMessage"
            :class="!showStateMessage ? 'bg-blue-500 hover:bg-blue-600 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
            class="mt-6 font-semibold py-2 px-4 rounded transition duration-300">

      {{ $t('auth.loginForm.submitButton') }}

    </button>

  </form>

</template>
<script>
import AuthService from "@/modules/auth/services/authService.js";

export default {
  name: 'LoginForm',
  emits: ["userTriesToLogIn"],
  props: {
    errorMessage: {
      type: String
    }
  },
  watch: {
    errorMessage() {
      if (this.errorMessage !== "") {
        this.showStateMessage = false;
      }
    }
  },
  data() {
    return {
      showStateMessage: false,
      email: "",
      password: "",
    }
  },
  methods: {
    async login() {
      this.showStateMessage = true;
      if (this.email === "" || this.password === "") return;
      this.$emit("userTriesToLogIn", this.email, this.password);
    }
    }
  }
</script>
