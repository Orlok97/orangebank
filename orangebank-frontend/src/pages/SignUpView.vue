<script setup>
import { onMounted, ref, watch} from 'vue';
import { useUserStore } from '@/stores/userStore';
const userStore=useUserStore();
const name=ref('')
const email=ref('')
const birthDate=ref(null)
const cpf=ref('')
const password=ref('')

watch(birthDate,()=>{
    if (!birthDate){
        return '';
    }
      const d = new Date(birthDate.value);
      const ano = d.getFullYear();
      const mes = String(d.getMonth() + 1).padStart(2, '0');
      const dia = String(d.getDate()).padStart(2, '0');
      birthDate.value= `${ano}-${mes}-${dia}`;
})

const createUser=async()=>{
    await userStore.createUser(name.value,email.value, birthDate.value, cpf.value, password.value)
}
</script>

<template>
    <div>
        <v-form @submit.prevent="createUser">
            <v-container>
                <v-row justify="center">
                    <v-col cols="12" md="6">
                        <v-text-field v-model="name" label="Nome"></v-text-field>
                    </v-col>
                </v-row>
                <v-row justify="center">
                    <v-col cols="12" md="6">
                        <v-text-field v-model="email" label="email"></v-text-field>
                    </v-col>
                </v-row>
                <v-row justify="center">
                    <v-col cols="12" md="6">
                        <v-date-picker v-model="birthDate"></v-date-picker>
                    </v-col>
                </v-row>
                <v-row justify="center">
                    <v-col cols="12" md="6">
                        <v-text-field v-model="cpf" label="CPF"></v-text-field>
                    </v-col>
                </v-row>

                <v-row justify="center">
                    <v-col cols="12" md="6">
                        <v-text-field type="password" v-model="password" label="senha"></v-text-field>
                    </v-col>
                </v-row>
                <v-row justify="center">
                    <v-btn color="deep-orange" type="submit">
                        Enviar
                    </v-btn>
                </v-row>
                
            </v-container>
        </v-form>
        <router-link class="text-white" to="/">Login</router-link>
    </div>
</template>