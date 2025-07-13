<script setup>
import { ref, watch, onMounted} from 'vue'
import { useUserStore } from '@/stores/userStore'

const userStore=useUserStore()
const items = [
    {
      title: 'Foo',
      value: 'foo',
    },
    {
      title: 'Bar',
      value: 'bar',
    },
    {
      title: 'Fizz',
      value: 'fizz',
    },
    {
      title: 'Buzz',
      value: 'buzz',
    },
  ]
onMounted(async ()=>{
    await userStore.getAuthUser()
})
const drawer = ref(false)
const group = ref(null)

watch(group, () => {
    drawer.value = false
  })
</script>

<template>
  <v-card v-if="!userStore.isLoading" >
    <v-layout>
      <v-app-bar color="deep-orange">
        <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

        <v-toolbar-title>OrangeBank</v-toolbar-title>

        <template v-if="$vuetify.display.mdAndUp">
          <v-btn icon="mdi-magnify" variant="text"></v-btn>

          <v-btn icon="mdi-filter" variant="text"></v-btn>
        </template>

        <v-btn icon="mdi-dots-vertical" variant="text"></v-btn>
      </v-app-bar>

      <v-navigation-drawer
        v-model="drawer"
        :location="$vuetify.display.mobile ? 'left' : undefined"
        temporary
      >
      <v-list>
          <v-list-item
            prepend-avatar="https://cdn-icons-png.flaticon.com/512/11018/11018596.png"
            :subtitle="userStore.user['email']"
            :title="userStore.user['name']"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>
        <v-list
          :items="items"
        ></v-list>
      </v-navigation-drawer>

      <v-main height="100vh">
        <v-container>
            <v-row class="bg-grey-lighten-2">
                <v-col cols="12">
                    <v-card class="bg-deep-orange-darken-1" variant="tonal"  :subtitle="userStore.user['name']" title="Conta Corrente" width="400" height="200">
                        <p class="text-h5 ml-5 my-5">$RS 0.00</p>
                    </v-card>
                </v-col>
                
            </v-row>
        </v-container>
        
      </v-main>
    </v-layout>
  </v-card>
</template>