<template>
  <div>
    <transition>
      <v-alert type="error" v-if="message.show">{{message.description}}</v-alert>
    </transition>
    <v-card>
      <v-card-text>
        <v-form v-model="isValid">
          <v-text-field
              label="Email"
              v-model="email"
              :rules="[v => !!v || 'Email inválido']"
              required
          >
          </v-text-field>
          <v-text-field
            label="Password"
            v-model="password"
            type="password"
            :rules="[v => !!v || 'Senha inválida']"
            required
          ></v-text-field>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-btn
          color="primary"
          :disabled="!isValid"
          @click="login"
        >Login
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
export default {
  name: "LoginForm",
  data: () => ({
    email: null,
    password: null,
    isValid: true,
    message: {
      show: false,
      description: '',
    }
  }),
  methods: {
    async login() {
      const payload = {
        email: this.email,
        password: this.password
      };
      await this.$store.dispatch('security/login', payload);
      const isAuthenticated = this.$store.getters['security/isAuthenticated'];

      if (isAuthenticated) {
        await this.$router.push({ name: 'BrokerageNoteListing'});
      } else {
        this.message.show = true;
        this.message.description = this.$store.getters['security/error'];
      }
    }
  }
}
</script>