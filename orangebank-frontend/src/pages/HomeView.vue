<script setup>
import { ref, watch, onMounted } from 'vue'
import { useUserStore } from '@/stores/userStore'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const authStore=useAuthStore()
const userStore = useUserStore()
const dialog = ref(false)

const router=useRouter()
const isSaldoVisible = ref(true);
const icon = ref('mdi-eye-off')
const tab = ref(null)
const extratoTab = ref('depositos')
const stocks = ref(null)
const fixedIncome = ref(null)
const stocksDialog=ref(false)
const drawer = ref(false)
const group = ref(null)

const items = [
    {
        title: 'Minha Conta',
        value: ''
    }
]


onMounted(async () => {

    await userStore.getAuthUser()
    await getStocks()
})

const getStocks = async () => {
    const response = await fetch('/assets-mock.json')
    if (response.ok) {
        const data = await response.json()
        fixedIncome.value = data.fixedIncome
        stocks.value = data.stocks
        console.log(data)
        console.log('ações', stocks.value)
    }
}
const toggleIcon = () => {
    isSaldoVisible.value = !isSaldoVisible.value;
    if (!isSaldoVisible.value) {
        icon.value = 'mdi-eye-outline'
    } else {
        icon.value = 'mdi-eye-off-outline'
    }
}
const handleLogout=async()=>{
    await authStore.logout(router)
}


watch(group, () => {
    drawer.value = false
})
</script>

<template>
    <v-card v-if="!userStore.isLoading">
        <v-layout>
            <v-app-bar color="deep-orange">
                <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

                <v-toolbar-title>OrangeBank</v-toolbar-title>

                <v-btn icon="mdi-logout" @click="handleLogout" variant="text"></v-btn>
            </v-app-bar>

            <v-navigation-drawer v-model="drawer" :location="$vuetify.display.mobile ? 'left' : undefined" temporary>
                <v-list>
                    <v-list-item prepend-avatar="https://cdn-icons-png.flaticon.com/512/11018/11018596.png"
                        :subtitle="userStore.user['email']" :title="userStore.user['name']"></v-list-item>
                </v-list>

                <v-divider></v-divider>
                <v-list :items="items"></v-list>
            </v-navigation-drawer>

            <v-main height="100vh">
                <v-container>
                    <v-tabs color="deep-orange" v-model="tab">
                        <v-tab value="conta">Conta Corrente</v-tab>
                        <v-tab value="investimentos">Meus Investimetos</v-tab>
                    </v-tabs>
                    <v-tabs-window v-model="tab">
                        <v-tabs-window-item value="conta">
                            <v-row class="">
                                <v-col cols="12">
                                    <v-card class="bg-deep-orange-darken-1" variant="tonal"
                                        :subtitle="userStore.user['name']" title="Conta Corrente" width="400"
                                        height="200">

                                        <div class="d-flex justify-space-between align-center my-5">
                                            <p v-if="isSaldoVisible" class="text-h5 mx-2">$RS 0.00</p>
                                            <p v-else class="text-h5 mx-2">$RS ****</p>
                                            <v-icon class="mx-4" :icon="icon" size="x-large"
                                                @click="toggleIcon"></v-icon>
                                        </div>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <v-row justify="center">
                                <v-col align="center" cols="6" md="2">
                                    <v-btn size="large" color="deep-orange" variant="outlined"
                                        @click="dialog = !dialog">
                                        Depositar
                                        <v-icon icon="mdi-cash-fast" end></v-icon>

                                    </v-btn>
                                </v-col>
                                <v-col align="center" cols="6" md="2">
                                    <v-btn size="large" color="deep-orange" variant="outlined"
                                        @click="dialog = !dialog">
                                        Transferir
                                        <v-icon icon="mdi-bank-transfer" end></v-icon>
                                    </v-btn>
                                </v-col>

                            </v-row>


                            <p class="text-h5 text-center mt-10">Extrato Bancario</p>
                            <v-tabs align-tabs="center" class="mt-1" color="deep-orange" v-model="extratoTab">
                                <v-tab value="depositos">Depósitos</v-tab>
                                <v-tab value="transferencias">Transferências</v-tab>
                            </v-tabs>

                            <v-tabs-window v-model="extratoTab">
                                <v-tabs-window-item value="depositos">
                                    <v-row justify="center">
                                        <v-col cols="12">
                                            <v-card title="Depósito">
                                                <div class="d-flex justify-space-between text-deep-orange">
                                                    <p class="mx-5">{{ userStore.user['name'] }}</p>
                                                    <p class="mx-5">12</p>
                                                </div>
                                            </v-card>
                                            <v-divider></v-divider>
                                            <v-card title="Depósito">
                                                <div class="d-flex justify-space-between text-deep-orange">
                                                    <p class="mx-5">{{ userStore.user['name'] }}</p>
                                                    <p class="mx-5">12</p>
                                                </div>
                                            </v-card>
                                        </v-col>
                                    </v-row>

                                </v-tabs-window-item>

                                <v-tabs-window-item value="transferencias">
                                    <v-row justify="center">
                                        <v-col cols="12">
                                            <v-card title="Transferência">
                                                <div class="d-flex justify-space-between text-deep-orange">
                                                    <p class="mx-5">{{ userStore.user['name'] }}</p>
                                                    <p class="mx-5">12</p>
                                                </div>
                                            </v-card>
                                            <v-divider></v-divider>
                                            <v-card title="Transferência">
                                                <div class="d-flex justify-space-between text-deep-orange">
                                                    <p class="mx-5">{{ userStore.user['name'] }}</p>
                                                    <p class="mx-5">12</p>
                                                </div>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </v-tabs-window-item>
                            </v-tabs-window>

                            <v-dialog v-model="dialog" width="auto">
                                <v-card width="400" prepend-icon="mdi-currency-usd"
                                    text="digite abaixo o valor a ser depositado." title="Depositar Saldo">
                                    <template v-slot:actions>
                                        <v-btn class="ms-auto" color="deep-orange" text="Ok"
                                            @click="dialog = false"></v-btn>
                                    </template>
                                    <v-text-field label="valor" type="number"></v-text-field>
                                </v-card>
                            </v-dialog>
                        </v-tabs-window-item>

                        <v-tabs-window-item value="investimentos">
                            <v-row justify="center">
                                <v-col cols="12">
                                    <v-card class="bg-deep-orange-darken-1" variant="tonal"
                                        :subtitle="userStore.user['name']" title="Investimentos" width="400"
                                        height="200">

                                        <div class="d-flex justify-space-between align-center my-5">
                                            <p v-if="isSaldoVisible" class="text-h5 mx-2">$RS 0.00</p>
                                            <p v-else class="text-h5 mx-2">$RS ****</p>
                                            <v-icon class="mx-4" :icon="icon" size="x-large" @click="toggleIcon">
                                            </v-icon>
                                        </div>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <v-row justify="center">
                                <v-col align="center" cols="6" md="2">
                                    <v-btn size="large" color="deep-orange" variant="outlined"
                                        @click="dialog = !dialog">
                                        Depositar
                                        <v-icon icon="mdi-cash-fast" end></v-icon>

                                    </v-btn>
                                </v-col>
                                <v-col align="center" cols="6" md="2">
                                    <v-btn size="large" color="deep-orange" variant="outlined"
                                        @click="stocksDialog = true">
                                        Comprar
                                        <v-icon icon="mdi-bitcoin" end></v-icon>
                                    </v-btn>
                                </v-col>

                            </v-row>
                            <p class="text-h5 text-center mt-10">Histórico de Compras</p>
                            <v-row justify="center">
                                <v-col cols="12">
                                    <v-card title="Compra">
                                        <div class="d-flex justify-space-between text-deep-orange">
                                            <p class="mx-5">{{ userStore.user['name'] }}</p>
                                            <p class="mx-5">12</p>
                                        </div>
                                    </v-card>
                                    <v-divider></v-divider>
                                    <v-card title="Compra">
                                        <div class="d-flex justify-space-between text-deep-orange">
                                            <p class="mx-5">{{ userStore.user['name'] }}</p>
                                            <p class="mx-5">12</p>
                                        </div>
                                    </v-card>
                                </v-col>
                            </v-row>
                            <v-dialog v-model="stocksDialog" width="auto">
                                <v-card max-width="400" prepend-icon="mdi-update"
                                    text="Your application will relaunch automatically after the update is complete."
                                    title="Update in progress">
                                    <template v-slot:actions>
                                        <v-btn class="ms-auto" text="Ok" @click="stockDialog = !stocksDialog"></v-btn>
                                    </template>
                                </v-card>
                            </v-dialog>
                        </v-tabs-window-item>
                    </v-tabs-window>
                </v-container>

            </v-main>
        </v-layout>
    </v-card>
</template>