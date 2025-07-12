<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/authStore';
const authStore = useAuthStore();
const email=ref('');
const password=ref('');

const handleLogin=async()=>{
    await authStore.login(email.value, password.value)
}
</script>

<template>
    <v-container>
        <v-row justify="center">
            Login page
        </v-row>
        <v-form @submit.prevent="handleLogin">
            <v-container>
                <v-row justify="center">
            <v-col cols="12" md="6">
                <v-form>
                    <v-text-field v-model="email" type="email" label="email"></v-text-field>
                </v-form>
            </v-col>
        </v-row>

        <v-row justify="center">
            <v-col cols="12" md="6">
                <v-form>
                    <v-text-field v-model="password" type="password" label="senha"></v-text-field>
                </v-form>
            </v-col>
        </v-row>

        <v-row justify="center">
            <v-btn type="submit" color="deep-orange">Entrar</v-btn>
        </v-row>
            </v-container>
        </v-form>
        
    </v-container>
</template>