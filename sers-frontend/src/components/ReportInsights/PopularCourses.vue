<template>
    <div>
        <div class = "table-container animate__animated animate__fadeIn animate__faster report-table">
            
            <div class="table-header">
                <h4>Top 5 Popular Courses</h4>
                <select id="select-year" v-model="chosenYear" @change="changeTop()">
                    <option v-for="year in years" :key="year" :value="year">{{year}}</option>
                </select>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Name</th>
                        <th scope = "col">No. of Sign-ups</th>
                        <th scope = "col">No. of Students Accepted</th>
                        <th scope = "col">Internal / External</th>
                        <th scope = "col">Category</th>
                        <th scope = "col">No. of New Sign-ups</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr v-for="course in popularCourseByYear" :key="course">
                        

                            <td>{{course.Course_Name}}</td>
                            <td>
                                <span v-if="course.Signups">
                                    {{course.Signups}}
                                </span>
                                <span v-else>
                                    0
                                </span>
                            </td>
                            <td>
                                <span v-if="course.Accepted">
                                    {{course.Accepted}}
                                </span>
                                <span v-else>
                                    0
                                </span>
                            </td>
                            <td>
                                <span v-if="!course.Instructor_IDs">
                                    NIL
                                </span>
                                <span v-else-if="!course.EUser_IDs">
                                    Internal
                                </span>
                                <span v-else-if="course.EUser_IDs != course.Instructor_IDs">Internal & External</span>
                                <span v-else>External</span>
                            </td>
                            <td>
                                {{course.Category}}
                            </td>
                            <td>
                                <span v-if="course.New_Signups">
                                    {{course.New_Signups}}
                                </span>
                                <span v-else>
                                    0
                                </span>
                            </td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
        <button type="button" class="report-table-btn" @click='exportData(chosenYear)'>Export to .csv</button>
    </div>
</template>
<script>
    import axios from 'axios'
    import {GETPOPULARCOURSE_URL} from '../../variables'
    export default{
        name: "PopularCourses",
        data(){
            return{
                counter: 0,
                popularCourses: [],
                top5: [],
                chosenYear: new Date().getFullYear()
            }
        },
        props:{
            years: Array,
            trigger: Boolean
        },
        methods:{
            changeTop(){
                this.count = 0
            },

            getPopularCourses(){
                axios.get(GETPOPULARCOURSE_URL,
                {withCredentials: true}
                ).then(response => (
                    this.popularCourses = response.data
                ))
            },
            exportData(year){
                const filtered_courses = this.popularCourseByYear.filter(function(row){
                    
                    if (row.Year == year){
                        
                        return (row)
                    }
                })
   

                const data = filtered_courses.map(row=>({
                    'Course Name': row.Course_Name,
                    'No. of Signups': row.Signups? row.Signups : 0,
                    'No. of Accepted': row.Accepted? row.Accepted : 0,
                    'Internal / External':  row.Instructor_IDs? row.EUser_IDs? row.EUser_IDs == row.Instructor_IDs? 'External' : 'Internal & External' : 'Internal' : 'NIL',
                    'Category': row.Category,
                    'No. of New Sign-ups': row.New_Signups? row.New_Signups : 0,
                }))
                // console.log(data)
                const csvText = this.objectToCSV(data)
                // console.log('text', csvText)
                this.downloadCSV(csvText)
            },
            objectToCSV(data){
                const csvRows = []

                const headers = Object.keys(data[0])

                csvRows.push(headers.join(','))


                for (const row of data){
                    const values = headers.map(header=>{
                        const escaped = (''+row[header]).replace(/"/g, '\\"')
                        return `"${escaped}"`
                    })

                    csvRows.push(values.join(','))

                }
   
                return csvRows.join('\n')
            },

            downloadCSV(data){
                const blob = new Blob([data], {type: 'text/csv'})
                const url = window.URL.createObjectURL(blob)
                const a = document.createElement('a')
                a.setAttribute('hidden', '')
                a.setAttribute('href', url)
                a.setAttribute('download', 'Popular_Courses_'+ this.chosenYear+'.csv' )
                document.body.appendChild(a)
                a.click()
                document.body.removeChild(a)

            }
        },
        mounted(){
            this.getPopularCourses()
        },
        computed:{
            popularCourseByYear: function(){
                
                var courseByYear = []

                for (const idx in this.popularCourses){
                    
                    if (this.popularCourses.length != 0 && this.popularCourses[idx].Year == this.chosenYear){
                        courseByYear.push(this.popularCourses[idx]);
                        if (courseByYear.length == 5){
                            return courseByYear;
                        }
                    }
                }
                return courseByYear;
            }
        },
        watch:{
            trigger (){
               this.exportData(this.chosenYear) 
            }
        }
    }
</script>
<style scoped>
</style>