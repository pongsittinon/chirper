<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;  
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */

   //หน้าที่:
//แสดงรายการ Chirps ทั้งหมด ที่อยู่ในฐานข้อมูลในหน้า Chirps/Index (Frontend Component)
//ใช้สำหรับดึงข้อมูลไปแสดงผลให้ผู้ใช้
   //ตัวอย่างการใช้งานในเว็บ:
//หน้า Home หรือหน้าฟีดแสดงโพสต์ทั้งหมด

    public function index(): Response 
    {
        //
        return Inertia::render('Chirps/Index', [
            //
            'chirps' => Chirp::with('user:id,name')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    //หน้าที่:
//รับข้อมูลที่ผู้ใช้ส่งมาจากฟอร์ม สร้าง Chirp
//ตรวจสอบข้อมูลให้ถูกต้อง (Validation)
//บันทึกข้อความในฐานข้อมูล (ผ่านผู้ใช้ที่ล็อกอินอยู่)
//Redirect กลับไปยังหน้ารายการ Chirps
    //ตัวอย่างการใช้งานในเว็บ:
//เมื่อผู้ใช้พิมพ์ข้อความและกดปุ่ม "Post"

    public function store(Request $request): RedirectResponse
    {
        //
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $request->user()->chirps()->create($validated);
 
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    //หน้าที่:
//รับข้อมูลใหม่จากฟอร์มแก้ไข Chirp
//ตรวจสอบสิทธิ์ (Authorize) เพื่อให้แน่ใจว่าผู้ใช้มีสิทธิ์แก้ไขโพสต์นั้น
//ตรวจสอบความถูกต้องของข้อมูล (Validation)
//อัปเดตข้อความในฐานข้อมูล
//Redirect กลับไปยังหน้ารายการ Chirps
    //ตัวอย่างการใช้งานในเว็บ:
//เมื่อผู้ใช้แก้ไขข้อความและกดปุ่ม "Save Changes"

    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        //
        Gate::authorize('update', $chirp);
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $chirp->update($validated);
 
        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */

    //หน้าที่:
//ตรวจสอบสิทธิ์ (Authorize) เพื่อให้แน่ใจว่าผู้ใช้มีสิทธิ์ลบโพสต์นั้น
//ลบ Chirp ออกจากฐานข้อมูล
//Redirect กลับไปยังหน้ารายการ Chirps
    //ตัวอย่างการใช้งานในเว็บ:
//เมื่อผู้ใช้คลิกปุ่ม "Delete" ใน Chirp ของตัวเอง

    public function destroy(Chirp $chirp): RedirectResponse
    {
        //
        Gate::authorize('delete', $chirp);
 
        $chirp->delete();
 
        return redirect(route('chirps.index'));
    }
}
    //สรุปหน้าที่โดยรวมของ ChirpController
//-เป็นตัวกลางในการจัดการ Chirps (CRUD: Create, Read, Update, Delete)
//-ประสานงานระหว่าง Frontend (ผ่าน Inertia.js) และ Backend (ผ่าน Laravel)
//-ตรวจสอบสิทธิ์การเข้าถึงและความถูกต้องของข้อมูลก่อนส่งต่อไปยังฐานข้อมูล