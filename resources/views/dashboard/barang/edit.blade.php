@extends('layouts.admin-shell')
@section('title','Edit Barang')
@section('content')

<div class="form-container">
    <div class="form-header">
        <div class="form-header-icon">
            <i class="fa-solid fa-pen-to-square"></i>
        </div>
        <div class="form-header-text">
            <h2>Edit Barang</h2>
            <p>Perbarui informasi barang yang sudah ada</p>
        </div>
    </div>

    <form id="form-edit" 
          action="{{ route('admin.barang.update', $barang->id_barang) }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Foto Section -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fa-solid fa-image"></i>
                Foto Barang
            </h3>
            
            <div class="photo-upload-area">
                @if($barang->foto)
                <div class="current-photo-preview">
                    <img src="{{ asset('storage/'.$barang->foto) }}" 
                         alt="Foto barang" 
                         id="preview-image">
                    <div class="photo-label">
                        <i class="fa-solid fa-check-circle"></i> Foto Saat Ini
                    </div>
                </div>
                @else
                <div class="no-photo-preview" id="no-photo-box">
                    <i class="fa-solid fa-image"></i>
                    <p>Belum ada foto</p>
                </div>
                @endif

                <div class="file-input-wrapper">
                    <input type="file" 
                           name="foto" 
                           id="foto-input" 
                           accept="image/*"
                           onchange="previewPhoto(event)">
                    <label for="foto-input" class="file-input-label">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Pilih Foto Baru</span>
                    </label>
                    <small class="form-hint">
                        <i class="fa-solid fa-info-circle"></i>
                        Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG, max 2MB
                    </small>
                </div>
            </div>
        </div>

        <!-- Data Barang Section -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fa-solid fa-box"></i>
                Informasi Barang
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label>
                        <i class="fa-solid fa-tag"></i>
                        Nama Barang
                    </label>
                    <input type="text" 
                           name="nama_barang" 
                           value="{{ $barang->nama_barang }}" 
                           placeholder="Masukkan nama barang"
                           required>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fa-solid fa-copyright"></i>
                        Merk Barang
                    </label>
                    <input type="text" 
                           name="merk_barang" 
                           value="{{ $barang->merk_barang }}" 
                           placeholder="Masukkan merk barang"
                           required>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fa-solid fa-calendar-days"></i>
                        Tanggal Pembelian
                    </label>
                    <input type="date" 
                           name="tanggal_pembelian" 
                           value="{{ $barang->tanggal_pembelian }}" 
                           required>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fa-solid fa-money-bill-wave"></i>
                        Harga Barang (Rp)
                    </label>
                    <input type="number" 
                           name="harga_barang" 
                           value="{{ $barang->harga_barang }}" 
                           placeholder="0"
                           min="0"
                           required>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fa-solid fa-cubes"></i>
                        Jumlah Stok
                    </label>
                    <input type="number" 
                           name="stok" 
                           value="{{ $barang->stok }}" 
                           placeholder="0"
                           min="0"
                           required>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions">
            <button type="button" 
                    class="btn btn-secondary" 
                    onclick="window.location='{{ route('admin.barang.index') }}'">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                Update Barang
            </button>
        </div>
    </form>
</div>

<script>
// Preview foto sebelum upload
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('preview-image');
            const noPhotoBox = document.getElementById('no-photo-box');
            
            if (previewImg) {
                previewImg.src = e.target.result;
            } else if (noPhotoBox) {
                noPhotoBox.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                `;
            }
        };
        reader.readAsDataURL(file);
    }
}

// Form submit confirmation
document.getElementById('form-edit').addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Konfirmasi Update',
        text: 'Apakah Anda yakin ingin mengupdate data barang ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1e88e5',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Update!',
        cancelButtonText: '<i class="fa-solid fa-xmark"></i> Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
});
</script>

<style>
/* Form Container */
.form-container {
    max-width: 550px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 10px;
    padding: 16px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
}

/* Form Header */
.form-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e3f2fd;
}

.form-header-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #1e88e5, #1565c0);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    box-shadow: 0 2px 8px rgba(30, 136, 229, 0.3);
}

.form-header-text h2 {
    margin: 0 0 2px 0;
    font-size: 17px;
    font-weight: 700;
    color: #1c1c1c;
}

.form-header-text p {
    margin: 0;
    color: #666;
    font-size: 11px;
}

/* Form Section */
.form-section {
    margin-bottom: 14px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid #f0f0f0;
}

.section-title i {
    color: #1e88e5;
    font-size: 14px;
}

/* Photo Upload Area */
.photo-upload-area {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.current-photo-preview,
.no-photo-preview {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    border: 2px solid #e3f2fd;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.current-photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-photo-preview {
    background: linear-gradient(135deg, #f5f5f5, #e8e8e8);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #999;
}

.no-photo-preview i {
    font-size: 28px;
    margin-bottom: 4px;
}

.no-photo-preview p {
    margin: 0;
    font-size: 9px;
}

.photo-label {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(30, 136, 229, 0.95), transparent);
    color: white;
    padding: 4px;
    text-align: center;
    font-size: 9px;
    font-weight: 600;
}

.photo-label i {
    margin-right: 4px;
}

.file-input-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.file-input-wrapper input[type="file"] {
    display: none;
}

.file-input-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 14px;
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    border: 2px dashed #1e88e5;
    border-radius: 7px;
    color: #1565c0;
    font-weight: 600;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-input-label:hover {
    background: linear-gradient(135deg, #bbdefb, #90caf9);
    border-color: #1565c0;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(30, 136, 229, 0.2);
}

.file-input-label i {
    font-size: 14px;
}

.form-hint {
    display: flex;
    align-items: flex-start;
    gap: 4px;
    color: #666;
    font-size: 9px;
    padding: 5px 6px;
    background: #f8f9fa;
    border-radius: 5px;
    border-left: 2px solid #1e88e5;
    line-height: 1.3;
}

.form-hint i {
    color: #1e88e5;
    font-size: 10px;
    margin-top: 1px;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 4px;
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
    font-size: 11px;
}

.form-group label i {
    color: #1e88e5;
    font-size: 12px;
}

.form-group input {
    padding: 7px 10px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    font-size: 11px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-group input:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.1);
}

.form-group input::placeholder {
    color: #aaa;
    font-size: 11px;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 16px;
    border: none;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: inherit;
}

.btn i {
    font-size: 12px;
}

.btn-secondary {
    background: #f5f5f5;
    color: #666;
    border: 2px solid #e0e0e0;
}

.btn-secondary:hover {
    background: #e8e8e8;
    color: #333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #1e88e5, #1565c0);
    color: white;
    box-shadow: 0 6px 16px rgba(30, 136, 229, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1565c0, #0d47a1);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(30, 136, 229, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
    .form-container {
        padding: 14px;
        border-radius: 8px;
    }

    .form-header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .form-header-icon {
        width: 34px;
        height: 34px;
        font-size: 16px;
    }

    .form-header-text h2 {
        font-size: 16px;
    }

    .photo-upload-area {
        flex-direction: column;
    }

    .current-photo-preview,
    .no-photo-preview {
        width: 100%;
        max-width: 100px;
        margin: 0 auto;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .form-container {
        padding: 12px;
    }

    .form-header-text h2 {
        font-size: 15px;
    }

    .section-title {
        font-size: 12px;
    }
}
</style>

@endsection