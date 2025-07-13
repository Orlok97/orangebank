import { defineStore } from "pinia";

export const useAuthStore=defineStore('auth',{
    actions:{
        async login(email, password, router){
            const response = await fetch('http://127.0.0.1:8000/api/auth',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json'
                },
                body:JSON.stringify({
                    email:email,
                    password:password
                })
            })
            if(!response.ok){
                throw new Error('erro ao efetuar o login')
            }
            const data=await response.json()
            sessionStorage.setItem('access_token',data.token)
            router.push('/dashboard')
            console.log(data.response)
        },
        async logout(router){
            const response=await fetch('http://127.0.0.1:8000/api/logout',{
                method:'POST',
                headers:{
                    'Content-type':'application/json',
                    'Authorization':`Bearer ${sessionStorage.getItem('access_token')}`
                }
            })
            if(!response.ok){
                throw new Error('erro ao encerrar a sessão')
            }
            const data = await response.json()
            console.log(data.response)
            router.push('/')
        }
    }
})