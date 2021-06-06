<template>
  <v-app>
    <v-app-bar app color="green darken-2">
      <div class="d-flex align-center">
        <v-img
          alt="Vuetify Logo"
          class="shrink mr-2"
          style="cursor: pointer"
          contain
          transition="scale-transition"
          width="40"
        />
      </div>

      <router-link to="/home" class="white--text mx-3 no-underline">Home</router-link>

      <v-spacer />

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn
              plain
              small
              color="green darken-2"
              class="white--text"
              v-bind="attrs"
              v-on="on"
          >
            Operações
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title>
              <router-link class="no-underline" to="/brokerageNotes">Notas de corretagem</router-link>
            </v-list-item-title>
          </v-list-item>
          <v-list-item>
            <v-list-item-title>
              <router-link class="no-underline" to="/positions">Posições</router-link>
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn
              plain
              small
              color="green darken-2"
              class="white--text mx-3"
              v-bind="attrs"
              v-on="on"
          >
            Imposto de Renda
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title>
              <router-link class="no-underline" to="/consolidations">Renda variável</router-link>
            </v-list-item-title>
          </v-list-item>
        </v-list>
        <v-list>
          <v-list-item>
            <v-list-item-title>
              <router-link class="no-underline" to="/taxes">DARF</router-link>
            </v-list-item-title>
          </v-list-item>
        </v-list>
        <v-list>
          <v-list-item>
            <v-list-item-title>
              <router-link class="no-underline" to="/goods">Bens e direitos</router-link>
            </v-list-item-title>
          </v-list-item>
        </v-list>
        <v-list>
          <v-list-item>
            <v-list-item-title>
              <router-link class="no-underline" to="/exempts">Rendimentos isentos</router-link>
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            plain
            small
            color="green darken-2"
            class="white--text"
            v-bind="attrs"
            v-on="on"
          >
            Tabelas
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title>
              <router-link class="no-underline" to="/brokers">Corretoras</router-link>
            </v-list-item-title>
          </v-list-item>
          <v-list-item>
            <v-list-item-title>
              <router-link class="no-underline" to="/companies">Empresas</router-link>
            </v-list-item-title>
          </v-list-item>
          <v-list-item>
            <v-list-item-title>
              <router-link class="no-underline" to="/assets">Ativos</router-link>
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-main>
      <v-container>
        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: "App",
}
</script>

<style scoped>
  .no-underline {
    text-decoration: none;
  }
</style>