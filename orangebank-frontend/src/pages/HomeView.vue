<script setup>
import { ref, watch, onMounted } from 'vue'
import { useUserStore } from '@/stores/userStore'
import { useCorrenteStore } from '@/stores/correnteStore'
import { useInvestimentoStore } from '@/stores/investimentoStore'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useTheme } from 'vuetify'

const theme = useTheme()
const themeIcon = ref('mdi-weather-sunny')
const isThemeDark = ref(true)
const authStore = useAuthStore()
const userStore = useUserStore()
const investimentoStore=useInvestimentoStore()
const correnteStore = useCorrenteStore()
const dialog = ref(false)
const dialogTransferencia = ref(false)
const valorDeposito = ref(0)
const valorTransferencia = ref(0)
const valorDepositoInvesimento=ref(0)
const dialogInvestimento=ref(false)
const contaID = ref(null)
const router = useRouter()
const isSaldoVisible = ref(true);
const icon = ref('mdi-eye-off')
const tab = ref(null)
const extratoTab = ref('depositos')
const stocks = ref(null)
const fixedIncome = ref(null)
const stocksDialog = ref(false)
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
    await correnteStore.getSaldo()
    await correnteStore.getExtrato()
    await investimentoStore.getSaldo()
    await investimentoStore.getExtrato()
    await getStocks()
})

const toggleTheme = () => {
    isThemeDark.value = !isThemeDark.value
    if (!isThemeDark.value) {
        themeIcon.value = 'mdi-moon-waxing-crescent'
    } else {
        themeIcon.value = 'mdi-weather-sunny'
    }
    theme.toggle()

}
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
const handleDepositaSaldo = async () => {
    console.log(valorDeposito.value, 'teste')
    await correnteStore.createDeposito(valorDeposito.value)
    dialog.value = false;
}
const handleDepositaInvestimento=async()=>{
    await investimentoStore.createDeposito(valorDepositoInvesimento.value)
    dialogInvestimento.value=false;
}

const handleTransfereSaldo = async () => {
    await correnteStore.trasnferirSaldo(contaID.value, valorTransferencia.value)
    dialogTransferencia.value = false;
}
const handleComprarAtivos=async(name,price)=>{
    await investimentoStore.comprarAtivos(name,price)
    stocksDialog.value=false;
}
const handleLogout = async () => {
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
                <v-btn :icon="themeIcon" @click="toggleTheme" variant="text"></v-btn>
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
                                            <p v-if="isSaldoVisible" class="text-h5 mx-2">$RS {{ correnteStore.saldo }}
                                            </p>
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
                                        @click="dialogTransferencia = !dialogTransferencia">
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
                                            <v-card v-for="extrato in correnteStore.extrato" title="Depósito">
                                                <div v-if="extrato.tipo === 'deposito'"
                                                    class="d-flex justify-space-between text-deep-orange">
                                                    <p class="mx-5">{{ userStore.user['name'] }}</p>
                                                    <p class="mx-5">{{ extrato.saldo }}</p>
                                                </div>
                                            </v-card>
                                            <v-divider></v-divider>
                                        </v-col>
                                    </v-row>

                                </v-tabs-window-item>

                                <v-tabs-window-item value="transferencias">
                                    <v-row justify="center">
                                        <v-col cols="12">
                                            <v-card v-for="extrato in correnteStore.extrato" title="Transferência">
                                                <div v-if="extrato.tipo === 'transferencia'" class="d-flex justify-space-between text-deep-orange">
                                                    <p class="mx-5">{{ userStore.user['name'] }}</p>
                                                    <p class="mx-5">{{ extrato.saldo }}</p>
                                                </div>
                                                <div v-else>
                                                    n
                                                </div>
                                            </v-card>
                                            <v-divider></v-divider>
                                        </v-col>
                                    </v-row>
                                </v-tabs-window-item>
                            </v-tabs-window>

                            <v-dialog v-model="dialog" width="auto">
                                <v-card width="400" prepend-icon="mdi-currency-usd"
                                    text="digite abaixo o valor a ser depositado." title="Depositar Saldo">

                                    <template v-slot:actions>
                                        <v-btn class="ms-auto" color="deep-orange" text="Ok"
                                            @click="handleDepositaSaldo"></v-btn>
                                    </template>
                                    <v-text-field label="valor" v-model="valorDeposito" type="number"></v-text-field>
                                </v-card>
                            </v-dialog>

                            <v-dialog v-model="dialogTransferencia" width="auto">
                                <v-card width="400" prepend-icon="mdi-currency-usd"
                                    text="digite abaixo o valor a ser transferido." title="Transferir Saldo">

                                    <template v-slot:actions>
                                        <v-btn class="ms-auto" color="deep-orange" text="Confirmar"
                                            @click="handleTransfereSaldo"></v-btn>
                                    </template>
                                    <v-text-field label="Numero da Conta" v-model="contaID"
                                        type="number"></v-text-field>
                                    <v-text-field label="valor" v-model="valorTransferencia"
                                        type="number"></v-text-field>
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
                                            <p v-if="isSaldoVisible" class="text-h5 mx-2">$RS {{ investimentoStore.saldo }}</p>
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
                                        @click="dialogInvestimento = !dialogInvestimento">
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
                                    <v-card v-for="extrato in investimentoStore.extrato " title="Compra">
                                        <div class="d-flex justify-space-between text-deep-orange">
                                            <p class="mx-5">{{ extrato.stock_name }}</p>
                                            <p class="mx-5">{{ extrato.stock_price }}</p>
                                        </div>
                                    </v-card>
                                    <v-divider></v-divider>
                                </v-col>
                            </v-row>
                            <v-dialog v-model="stocksDialog" width="500" max-width="500">
                                <v-card>
                                    <v-card-title class="text-h6">Lista de Ações</v-card-title>
                                    <v-divider></v-divider>

                                    <div style="max-height: 400px; overflow-y: auto;">
                                        <div v-for="stock in stocks" :key="stock.symbol">
                                            <v-card flat>
                                                <v-card-title>{{ stock.name }}</v-card-title>
                                                <v-card-text>Preço atual: {{ stock.currentPrice }}</v-card-text>
                                                <v-card-actions>
                                                    <v-btn color="deep-orange" @click="handleComprarAtivos(stock.name,stock.currentPrice)">Comprar</v-btn>
                                                </v-card-actions>
                                            </v-card>
                                            <v-divider></v-divider>
                                        </div>
                                    </div>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn text @click="stocksDialog = false">Fechar</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <v-dialog v-model="dialogInvestimento" width="auto">
                                <v-card width="400" prepend-icon="mdi-currency-usd"
                                    text="digite abaixo o valor a ser depositado." title="Depositar Saldo">

                                    <template v-slot:actions>
                                        <v-btn class="ms-auto" color="deep-orange" text="Ok"
                                            @click="handleDepositaInvestimento"></v-btn>
                                    </template>
                                    <v-text-field label="valor" v-model="valorDepositoInvesimento" type="number"></v-text-field>
                                </v-card>
                            </v-dialog>
                        </v-tabs-window-item>
                    </v-tabs-window>
                </v-container>

            </v-main>
        </v-layout>
    </v-card>
</template>