import { defineStore } from "pinia";
import { da } from "vuetify/locale";

export const useUserStore=defineStore('user',{
    state:()=>({
        user:null,
        isLoading:true
    }),
    actions:{
        async getAuthUser(){
            const response=await fetch('http://127.0.0.1:8000/api/user/auth',{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Authorization':`Bearer ${sessionStorage.getItem('access_token')}`
                }
            })
            if(!response.ok){
                throw new Error('erro ao buscar os dados do usuario')
            }
            const data=await response.json()
            if(data){
                this.user=data.response
                console.log(data.response)
                this.isLoading=false;
            }
        }
        ,
        async createUser(name, email,birthDate,CPF,password){
            const response=await fetch('http://127.0.0.1:8000/api/user',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json'
                },
                body:JSON.stringify({
                    name:name,
                    email:email,
                    birthDate:birthDate,
                    CPF:CPF,
                    password:password
                })
            })
            if(!response){
                throw new Error('erro ao cadastrar o usuario')
            }
            const data=await response.json()
            console.log(data.response)
        }
    }
})