import { useState } from 'react'
import { Inertia } from '@inertiajs/react'

export default function Index({ students, flash }) {
    const [form, setForm] = useState({ student_name:'', student_email:'', phone_no:'' })
    const [editId, setEditId] = useState(null)

    const handleSubmit = (e) => {
        e.preventDefault()

        if(editId){
            Inertia.put(route('students.update', editId), form, {
                onSuccess: () => {
                    setForm({ student_name:'', student_email:'', phone_no:'' })
                    setEditId(null)
                }
            })
        } else {
            Inertia.post(route('students.store'), form, {
                onSuccess: () => setForm({ student_name:'', student_email:'', phone_no:'' })
            })
        }
    }

    const handleEdit = (student) => {
        setForm({
            student_name: student.student_name,
            student_email: student.student_email,
            phone_no: student.phone_no
        })
        setEditId(student.student_id)
    }

    const handleDelete = (id) => {
        if(confirm('Are you sure?')) {
            Inertia.delete(route('students.destroy', id))
        }
    }

    return (
        <div>
            <h1>Student List</h1>

            {flash.success && <div style={{color:'green'}}>{flash.success}</div>}
            {flash.failed && <div style={{color:'red'}}>{flash.failed}</div>}

            <form onSubmit={handleSubmit} style={{marginBottom:'20px'}}>
                <input value={form.student_name} onChange={e=>setForm({...form, student_name:e.target.value})} placeholder='NAME' />
                <input value={form.student_email} onChange={e=>setForm({...form, student_email:e.target.value})} placeholder='EMAIL' />
                <input value={form.phone_no} onChange={e=>setForm({...form, phone_no:e.target.value})} placeholder='CONTACT' />
                <button type='submit'>{editId ? 'Update' : 'Add'} Student</button>
            </form>

            <table border={1} cellPadding={5}>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {students.data.map(s => (
                        <tr key={s.student_id}>
                            <td>{s.student_name}</td>
                            <td>{s.student_email}</td>
                            <td>{s.phone_no}</td>
                            <td>
                                <button onClick={()=>handleEdit(s)}>Edit</button>
                                <button onClick={()=>handleDelete(s.student_id)}>Delete</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <div style={{marginTop:'10px'}}>
                {students.prev_page_url && <button onClick={() => Inertia.visit(students.prev_page_url)}>Prev</button>}
                {students.next_page_url && <button onClick={() => Inertia.visit(students.next_page_url)}>Next</button>}
            </div>
        </div>
    )
}
