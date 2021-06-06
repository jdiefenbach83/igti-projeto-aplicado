<template>
  <div>
    <div class='d-flex align-center'>
      <h1>Login</h1>
    </div>
    <hr class='mb-3'/>
    <login-form />
  </div>
</template>

<script>
import LoginForm from "@/components/Security/Login";

export default {
  name: 'Login',
  components: { LoginForm },
}
</script>
