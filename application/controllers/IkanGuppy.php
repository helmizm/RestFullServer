<?php

use chriskacerguis\RestServer\RestController;
class IkanGuppy extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ikanguppy_model', 'igy');
    }

    public function index_get()
    {
        $id = $this->get('id');
        if ($id === null){
            $p = $this->get('page');
            $p = (empty($p) ? 1 : $p);
            $total_data = $this->igy->count();
            $total_page = ceil($total_data / 5);
            $start = ($p - 1) * 5;
            $list = $this->igy->get(null, 5, $start);
            if ($list){
            $data = [
                'status' => true,
                'page' => $p,
                'total_data' => $total_data,
                'total_page' => $total_page,
                'data' => $list
            ];
        } else {
            $data=['status'=>false,
            'msg'=>'Data tidak ditemukan'];
        }
        $this->response($data, RestController::HTTP_OK);
        } else {
            $data = $this->igy->get($id);
            if ($data){
                $this->response(['status' => true, 'data' => $data], RestController::HTTP_OK);   
            } else {
                $this->response(['status' => false, 'msg' => $id .' tidak ditemukan'], RestController::HTTP_NOT_FOUND);
            }
        }
    }  
    
    public function index_post()
    {
        $data = [
            'id' => $this->post('id'),
            'nama_ikan' => $this->post('nama'),
            'jenis_ikan' => $this->post('jenis'),
            'kualitas_air' => $this->post('kualitas'),
            'perawatan' => $this->post('perawatan'),
            'penyakit' => $this->post('penyakit'),
            'pengobatan' => $this->post('pengobatan')
        ];
        $simpan = $this->igy->add($data);
        if ($simpan['status']) {
           $this->response(['status' => true, 'msg' => $simpan['data']. ' Data telah ditambahkan'], RestController::HTTP_CREATED);
        } else {
           $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    public function index_put()
    {
        $data = [
            'id' => $this->put('id'),
            'nama_ikan' => $this->put('nama'),
            'jenis_ikan' => $this->put('jenis'),
            'kualitas_air' => $this->put('kualitas'),
            'perawatan' => $this->put('perawatan'),
            'penyakit' => $this->put('penyakit'),
            'pengobatan' => $this->put('pengobatan')
        ];
        $id = $this->put('id');
        if ($id === null) {
            $this->response(['status' => false, 'msg' => 'Masukkan ID yang akan dirubah'], RestController::HTTP_BAD_REQUEST);
        }
        $simpan = $this->igy->update($id, $data);
        if ($simpan['status']) {
            $status = (int)$simpan['data'];
            if ($status > 0)
           $this->response(['status' => true, 'msg' => $simpan['data'] . ' Data telah dirubah'], RestController::HTTP_OK);
           else 
           $this->response(['status' => false, 'msg' => 'Tidak ada data yang dirubah'], RestController::HTTP_BAD_REQUEST);
        } else {
           $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    public function index_delete(){
        $id = $this->delete('id');
        if ($id === null) {
            $this->response(['status' => false, 'msg' => 'Masukkan ID yang akan dihapus'], RestController::HTTP_BAD_REQUEST);
        }
        $delete = $this->igy->delete($id);
        if ($delete['status']) {
            $status = (int)$delete['data'];
            if ($status > 0)
           $this->response(['status' => true, 'msg' => $id . ' Data telah dihapus'], RestController::HTTP_OK);
           else 
           $this->response(['status' => false, 'msg' => 'Tidak ada data yang dihapus'], RestController::HTTP_BAD_REQUEST);
        } else {
           $this->response(['status' => false, 'msg' => $delete['msg']], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}
?>