<template>
  <TopBar/>
  <main class="register-screen w-4/5 mx-auto h-[calc(100vh-5rem)] mt-24">

    <h2 class="text-3xl font-semibold mb-4">{{ $t('auth.registerForm.title') }}</h2>
    <p class="text-gray-600">{{ $t('auth.registerForm.instructions') }}</p>

    <RegisterForm
        @onRegister="Register"
        v-bind:errorMessages="errorMessages"
      />

    <p class="mt-4 text-gray-600">

      {{ $t('auth.registerForm.loginPrompt') }}

      <RouterLink class="text-blue-500" to="/login">
        {{ $t('auth.registerForm.loginLink') }}
      </RouterLink>

    </p>

  </main>

</template>


<script>
import RegisterForm from "@/modules/auth/components/RegisterForm.vue";
import TopBar from "@/modules/core/components/TopBar.vue";
import AuthService from "@/modules/auth/services/authService.js";

export default {
  name: 'RegisterView',
  components: {
    TopBar,
    RegisterForm,
  },
  data() {
    return {
      service: new AuthService(),
      errorMessages: "",
      lang: this.$i18n.locale
    };
  },
  methods: {
   async Register(name, email, password, confirmPassword) {
     this.errorMessages = "";

      this.service.setName(name);
      this.service.setEmail(email);
      this.service.setPassword(password);
      this.service.setConfirmPassword(confirmPassword);

      await this.service.register(this.lang);

      const error = this.service.getError();
      if (error) {
        this.showStateMessage = false;
        this.errorMessages = error.errors;
      } else {
        this.$router.push({name: "login"});
      }
    }
  }
}
</script>
