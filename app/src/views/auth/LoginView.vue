<template>
  <TopBar/>
  <main class="login-screen w-4/5 mx-auto h-[calc(100vh-5rem)] mt-24">

    <h2 class="text-3xl font-semibold mb-4">{{ $t('auth.loginForm.title') }}</h2>
    <p class="text-gray-600">{{ $t('auth.loginForm.instructions') }}</p>

    <LoginForm
        @userTriesToLogIn="handleUserLogin"
        v-bind:errorMessage="errorMessage"
    />

    <p class="mt-4 text-gray-600">

      {{ $t('auth.loginForm.registerPrompt') }}

      <RouterLink class="text-blue-500" to="register">
        {{ $t('auth.loginForm.registerLink') }}
      </RouterLink>

    </p>

  </main>

</template>

<script>
import LoginForm from "@/modules/auth/components/LoginForm.vue";
import TopBar from "@/modules/core/components/TopBar.vue";
import AuthService from "@/modules/auth/services/authService.js";

export default {
  name: 'LoginView',
  components: {
    TopBar,
    LoginForm
  },
  data() {
    return {
      userDetails: null,
      errorMessage: "",
      service: new AuthService(),
      lang: this.$i18n.locale
    };
  },
  methods: {
    async handleUserLogin(email, password) {
      this.errorMessage = "";

      this.service.setEmail(email);
      this.service.setPassword(password);

      await this.service.login(this.lang);

      const error = this.service.getError();
      if (error) {
        this.showStateMessage = false;
        this.errorMessage = error.message;
      } else {

        const userDetails = this.service.getUserProfile(this.lang);

        if (userDetails["is_admin"]) {
          this.$router.push({
            name: "dashboard",
            params: {month: new Date().getMonth() + 1, year: new Date().getFullYear()}
          });
        } else {
          this.$router.push({
            name: "dashboard",
            params: {month: new Date().getMonth() + 1, year: new Date().getFullYear()}
          });
        }
      }
    }
  }
}
</script>