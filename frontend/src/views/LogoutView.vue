<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

onMounted(() => {
  localStorage.clear()
  document.cookie = "harmony_token=; expires=Thu, 01 Jan 1970 00:00:00 GMT; Secure; SameSite=None";
  
  router.push('/')
})
</script>
