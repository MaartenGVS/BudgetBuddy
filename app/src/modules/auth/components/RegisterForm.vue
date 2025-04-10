<template>
  <form @submit.prevent="register" class="login-form mt-6">

    <p class="text-red-500" v-for="(errorMessage, index) in errorMessages" :key="index">
      {{ errorMessage.join('') }}
    </p>
    <p class="text-green-500" v-show="showStateMessage">{{ $t('auth.registerForm.stateMessage') }}</p>

    <div class="form-group mt-4">

      <label for="name" class="block text-gray-700">{{ $t('auth.registerForm.fullName') }}</label>
      <input type="text" v-model="name" :placeholder="$t('auth.registerForm.fullName')" required id="name"
             class="form-input mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"/>

    </div>

    <div class="form-group mt-4">

      <label for="email" class="block text-gray-700">{{ $t('auth.registerForm.email') }}</label>
      <input type="email" v-model="email" :placeholder="$t('auth.registerForm.email')" required id="email"
             class="form-input mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"/>

    </div>

    <div class="form-group mt-4">

      <label for="password" class="block text-gray-700">{{ $t('auth.registerForm.password') }}</label>
      <input type="password" v-model="password" :placeholder="$t('auth.loginForm.password')" required id="password"
             class="form-input mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"/>

    </div>

    <div class="form-group mt-4">

      <label for="password" class="block text-gray-700">{{ $t('auth.registerForm.confirmPassword') }}</label>
      <input type="password" v-model="confirmPassword" :placeholder="$t('auth.registerForm.confirmPassword')"
             id="password" required
             class="form-input mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"/>

    </div>


    <button type="submit"
            :disabled="showStateMessage"
            :class="!showStateMessage ? 'bg-blue-500 hover:bg-blue-600 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
            class="mt-6 font-semibold py-2 px-4 rounded transition duration-300">

      {{ $t('auth.registerForm.submitButton') }}

    </button>

  </form>

</template>


<script>
import AuthService from "@/modules/auth/services/authService.js";

export default {
  name: "RegisterForm",
  emits: ["onRegister"],
  props: {
    errorMessages: {
      type: String
    }
  },
  watch: {
    errorMessages() {
      if (this.errorMessages !== "") {
        this.showStateMessage = false;
      }
    }
  },
  data() {
    return {
      showStateMessage: false,
      name: "",
      email: "",
      password: "",
      confirmPassword: "",
      lang: this.$i18n.locale
    }
  },
  methods: {
    async register() {
      if (this.email === "" || this.password === "" || this.name === "" || this.confirmPassword === "") return;
      this.showStateMessage = true;
      this.$emit("onRegister", this.name, this.email, this.password, this.confirmPassword);
    }

  }
}
</script>
