<?php

namespace App\Controllers;

use App\Models\RecordModel;

class Records extends BaseController
{
    public function index()
    {
        $model = new RecordModel();
        $data['records'] = $model->findAll();

        return view('records/index', $data);
    }

    public function create()
    {
        return view('records/create');
    }

    public function store()
    {
        $model = new RecordModel();

        $model->save([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status'),
            'category'    => $this->request->getPost('category'),
        ]);

        return redirect()->to(site_url('records'))->with('success', 'Record created');
    }

    public function edit($id)
    {
        $model = new RecordModel();
        $data['record'] = $model->find($id);

        if (!$data['record']) {
            return redirect()->to(site_url('records'))->with('error', 'Record not found');
        }

        return view('records/edit', $data);
    }

    public function update($id)
    {
        $model = new RecordModel();

        $model->update($id, [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'status'      => $this->request->getPost('status'),
            'category'    => $this->request->getPost('category'),
        ]);

        return redirect()->to(site_url('records'))->with('success', 'Record updated');
    }

    public function delete($id)
    {
        $model = new RecordModel();
        $model->delete($id);

        return redirect()->to(site_url('records'))->with('success', 'Record deleted');
    }
}