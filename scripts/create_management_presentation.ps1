$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$outputDir = Join-Path $projectRoot 'docs'
if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir | Out-Null
}

$outputPath = Join-Path $outputDir 'Presentasi-Manfaat-App-GA.pptx'
$logoPath = Join-Path $projectRoot 'public\icons\GA-SCM.png'

function Set-TextRangeStyle {
    param(
        $TextRange,
        [int]$FontSize = 20,
        [string]$FontName = 'Aptos',
        [int]$Color = 0x203040,
        [bool]$Bold = $false
    )

    $TextRange.Font.Name = $FontName
    $TextRange.Font.Size = $FontSize
    $TextRange.Font.Bold = [int]$Bold
    $TextRange.Font.Color.RGB = $Color
}

function Add-TitleSlide {
    param($Presentation, [string]$Title, [string]$Subtitle)

    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 1)
    $slide.FollowMasterBackground = -1
    $slide.Background.Fill.ForeColor.RGB = 0xF6F8FB

    $titleShape = $slide.Shapes.Title
    $titleShape.Left = 45
    $titleShape.Top = 70
    $titleShape.Width = 780
    $titleShape.Height = 100
    $titleShape.TextFrame.TextRange.Text = $Title
    Set-TextRangeStyle -TextRange $titleShape.TextFrame.TextRange -FontSize 28 -FontName 'Aptos Display' -Color 0x17365D -Bold $true

    $subtitleShape = $slide.Shapes.Placeholders.Item(2)
    $subtitleShape.Left = 48
    $subtitleShape.Top = 180
    $subtitleShape.Width = 780
    $subtitleShape.Height = 180
    $subtitleShape.TextFrame.TextRange.Text = $Subtitle
    Set-TextRangeStyle -TextRange $subtitleShape.TextFrame.TextRange -FontSize 17 -Color 0x4A4A4A

    if (Test-Path $logoPath) {
        $slide.Shapes.AddPicture($logoPath, $false, $true, 645, 28, 120, 120) | Out-Null
    }

    $accent = $slide.Shapes.AddShape(1, 45, 330, 240, 8)
    $accent.Fill.ForeColor.RGB = 0x19A58B
    $accent.Line.Visible = 0
}

function Add-BulletSlide {
    param(
        $Presentation,
        [string]$Title,
        [string[]]$Bullets,
        [string]$Footer = ''
    )

    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 2)
    $slide.Background.Fill.ForeColor.RGB = 0xFFFFFF

    $titleShape = $slide.Shapes.Title
    $titleShape.TextFrame.TextRange.Text = $Title
    Set-TextRangeStyle -TextRange $titleShape.TextFrame.TextRange -FontSize 26 -FontName 'Aptos Display' -Color 0x17365D -Bold $true

    $body = $slide.Shapes.Placeholders.Item(2)
    $body.Left = 55
    $body.Top = 110
    $body.Width = 820
    $body.Height = 360
    $body.TextFrame.TextRange.Text = ''

    for ($i = 0; $i -lt $Bullets.Count; $i++) {
        $paragraph = $body.TextFrame.TextRange.Paragraphs(($i + 1))
        $paragraph.Text = $Bullets[$i]
        $paragraph.ParagraphFormat.Bullet.Visible = -1
        $paragraph.ParagraphFormat.Bullet.Character = 8226
        $paragraph.ParagraphFormat.SpaceAfter = 10
        Set-TextRangeStyle -TextRange $paragraph -FontSize 20 -Color 0x333333
    }

    if ($Footer) {
        $note = $slide.Shapes.AddTextbox(1, 55, 485, 820, 32)
        $note.TextFrame.TextRange.Text = $Footer
        Set-TextRangeStyle -TextRange $note.TextFrame.TextRange -FontSize 11 -Color 0x6B6B6B
    }
}

function Add-TwoColumnSlide {
    param(
        $Presentation,
        [string]$Title,
        [string]$LeftTitle,
        [string[]]$LeftBullets,
        [string]$RightTitle,
        [string[]]$RightBullets
    )

    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = 0xF8FAFC

    $titleBox = $slide.Shapes.AddTextbox(1, 45, 25, 830, 40)
    $titleBox.TextFrame.TextRange.Text = $Title
    Set-TextRangeStyle -TextRange $titleBox.TextFrame.TextRange -FontSize 24 -FontName 'Aptos Display' -Color 0x17365D -Bold $true

    $leftPanel = $slide.Shapes.AddShape(1, 45, 90, 390, 380)
    $leftPanel.Fill.ForeColor.RGB = 0xFFFFFF
    $leftPanel.Line.ForeColor.RGB = 0xD9E2F2

    $rightPanel = $slide.Shapes.AddShape(1, 455, 90, 390, 380)
    $rightPanel.Fill.ForeColor.RGB = 0xFFFFFF
    $rightPanel.Line.ForeColor.RGB = 0xD9E2F2

    $leftTitleBox = $slide.Shapes.AddTextbox(1, 65, 110, 330, 30)
    $leftTitleBox.TextFrame.TextRange.Text = $LeftTitle
    Set-TextRangeStyle -TextRange $leftTitleBox.TextFrame.TextRange -FontSize 20 -FontName 'Aptos Display' -Color 0x0E5A8A -Bold $true

    $leftBody = $slide.Shapes.AddTextbox(1, 65, 150, 330, 290)
    for ($i = 0; $i -lt $LeftBullets.Count; $i++) {
        $p = $leftBody.TextFrame.TextRange.Paragraphs(($i + 1))
        $p.Text = $LeftBullets[$i]
        $p.ParagraphFormat.Bullet.Visible = -1
        $p.ParagraphFormat.Bullet.Character = 8226
        $p.ParagraphFormat.SpaceAfter = 8
        Set-TextRangeStyle -TextRange $p -FontSize 17 -Color 0x333333
    }

    $rightTitleBox = $slide.Shapes.AddTextbox(1, 475, 110, 330, 30)
    $rightTitleBox.TextFrame.TextRange.Text = $RightTitle
    Set-TextRangeStyle -TextRange $rightTitleBox.TextFrame.TextRange -FontSize 20 -FontName 'Aptos Display' -Color 0x0E5A8A -Bold $true

    $rightBody = $slide.Shapes.AddTextbox(1, 475, 150, 330, 290)
    for ($i = 0; $i -lt $RightBullets.Count; $i++) {
        $p = $rightBody.TextFrame.TextRange.Paragraphs(($i + 1))
        $p.Text = $RightBullets[$i]
        $p.ParagraphFormat.Bullet.Visible = -1
        $p.ParagraphFormat.Bullet.Character = 8226
        $p.ParagraphFormat.SpaceAfter = 8
        Set-TextRangeStyle -TextRange $p -FontSize 17 -Color 0x333333
    }
}

function Add-ImpactSlide {
    param($Presentation)

    $slide = $Presentation.Slides.Add($Presentation.Slides.Count + 1, 12)
    $slide.Background.Fill.ForeColor.RGB = 0xFFFFFF

    $titleBox = $slide.Shapes.AddTextbox(1, 45, 24, 830, 38)
    $titleBox.TextFrame.TextRange.Text = 'Simulasi Dampak Jika Sistem Berjalan Konsisten'
    Set-TextRangeStyle -TextRange $titleBox.TextFrame.TextRange -FontSize 24 -FontName 'Aptos Display' -Color 0x17365D -Bold $true

    $subBox = $slide.Shapes.AddTextbox(1, 45, 60, 830, 24)
    $subBox.TextFrame.TextRange.Text = 'Asumsi sederhana berbasis volume laporan saat ini: sekitar 115 laporan per periode.'
    Set-TextRangeStyle -TextRange $subBox.TextFrame.TextRange -FontSize 12 -Color 0x666666

    $cards = @(
        @{ X = 45;  Title = 'Lost Report Turun'; Value = '23 -> 6'; Note = 'Jika laporan hilang turun dari 20% menjadi 5%, sekitar 17 laporan tambahan dapat ditangani.'; Color = 0xE8F4FD },
        @{ X = 255; Title = 'Monitoring Nyata'; Value = '100%'; Note = 'Seluruh tiket punya status, PIC, dan histori tindak lanjut yang bisa dipantau owner.'; Color = 0xEAF7F1 },
        @{ X = 465; Title = 'Respon Lebih Cepat'; Value = '+30%'; Note = 'Potensi kenaikan kepatuhan respon karena dashboard memudahkan prioritas dan follow-up.'; Color = 0xFFF4E5 },
        @{ X = 675; Title = 'Citra GA Naik'; Value = 'Terukur'; Note = 'Service quality lebih mudah dibuktikan lewat data closed, progress, top report, dan feedback.'; Color = 0xF3EEFF }
    )

    foreach ($card in $cards) {
        $shape = $slide.Shapes.AddShape(1, $card.X, 110, 180, 210)
        $shape.Fill.ForeColor.RGB = $card.Color
        $shape.Line.Visible = 0

        $tb = $slide.Shapes.AddTextbox(1, $card.X + 12, 125, 155, 25)
        $tb.TextFrame.TextRange.Text = $card.Title
        Set-TextRangeStyle -TextRange $tb.TextFrame.TextRange -FontSize 16 -FontName 'Aptos Display' -Color 0x17365D -Bold $true

        $vb = $slide.Shapes.AddTextbox(1, $card.X + 12, 160, 155, 44)
        $vb.TextFrame.TextRange.Text = $card.Value
        Set-TextRangeStyle -TextRange $vb.TextFrame.TextRange -FontSize 24 -FontName 'Aptos Display' -Color 0x19A58B -Bold $true

        $nb = $slide.Shapes.AddTextbox(1, $card.X + 12, 210, 155, 95)
        $nb.TextFrame.TextRange.Text = $card.Note
        Set-TextRangeStyle -TextRange $nb.TextFrame.TextRange -FontSize 12 -Color 0x444444
    }

    $footer = $slide.Shapes.AddTextbox(1, 45, 350, 810, 90)
    $footer.TextFrame.TextRange.Text = "Dampak utama bagi GA: lebih sedikit laporan yang hilang, proses kerja lebih disiplin, owner mendapat kontrol penuh, dan service excellence lebih mudah dibangun karena ada data untuk reward, evaluasi, dan perbaikan."
    Set-TextRangeStyle -TextRange $footer.TextFrame.TextRange -FontSize 18 -Color 0x333333
}

$powerPoint = $null
$presentation = $null

try {
    $powerPoint = New-Object -ComObject PowerPoint.Application
    $powerPoint.Visible = -1
    $presentation = $powerPoint.Presentations.Add()
    $presentation.PageSetup.SlideSize = 16

    Add-TitleSlide -Presentation $presentation `
        -Title 'SCM GA Complaint Management App' `
        -Subtitle 'Presentasi manfaat aplikasi untuk management: memperkuat pelaporan, monitoring service, dan peningkatan kualitas layanan General Affairs.'

    Add-BulletSlide -Presentation $presentation `
        -Title 'Latar Belakang Permasalahan Saat Ini' `
        -Bullets @(
            'Banyak laporan masuk ke Aden, tetapi respon dan penyelesaiannya belum terpantau secara konsisten.',
            'Sebagian laporan berpotensi lost karena tidak tercatat dalam satu sistem yang sama.',
            'Management belum memiliki monitoring real-time untuk melihat beban kerja, status, dan bottleneck layanan.',
            'Tanpa data yang rapi, sulit menentukan prioritas perbaikan dan mengukur kualitas service GA.'
        )

    Add-BulletSlide -Presentation $presentation `
        -Title 'Apa Manfaat Utama Aplikasi Ini' `
        -Bullets @(
            'Semua laporan masuk ke satu pintu dan otomatis menjadi tiket yang dapat dilacak.',
            'Setiap tiket memiliki status, tipe layanan, waktu laporan, dan histori tindak lanjut.',
            'Dashboard membuat owner dan sub user bisa memonitor progres secara penuh.',
            'Data top pelapor membantu membangun budaya partisipatif: semua orang bisa menjadi agen perbaikan service.',
            'Ke depan, reward untuk pelapor dan tim service bisa diberikan berdasarkan data nyata dan feedback.'
        )

    Add-TwoColumnSlide -Presentation $presentation `
        -Title 'Tahap 1: Proses Aplikasi untuk User Melapor' `
        -LeftTitle 'Alur User' `
        -LeftBullets @(
            'User membuka form laporan dari HP atau desktop.',
            'User memilih tipe layanan: Receptionist, Housekeeping, atau Laundry.',
            'User mengisi lokasi, deskripsi masalah, dan foto pendukung.',
            'Sistem membuat nomor tiket otomatis sehingga laporan tidak mudah hilang.',
            'User dapat melakukan tracking status laporan.'
        ) `
        -RightTitle 'Nilai Bisnis' `
        -RightBullets @(
            'Laporan lebih tertib dan terdokumentasi.',
            'Mengurangi ketergantungan pada chat personal.',
            'Memudahkan prioritas penanganan berdasarkan data.',
            'Meningkatkan rasa percaya user karena laporan punya bukti tindak lanjut.',
            'Memberi dasar data untuk evaluasi kualitas layanan.'
        )

    Add-TwoColumnSlide -Presentation $presentation `
        -Title 'Tahap 2: Dashboard Operasional dan Monitoring Penuh' `
        -LeftTitle 'Struktur Pengguna' `
        -LeftBullets @(
            '4 sub user operasional: Superadmin, Receptionist, Housekeeping, dan Laundry.',
            'Masing-masing tim fokus pada tipe laporan yang menjadi tanggung jawabnya.',
            'Owner atau management dapat memantau keseluruhan performa dari satu dashboard.'
        ) `
        -RightTitle 'Kemampuan Dashboard' `
        -RightBullets @(
            'Melihat total laporan, open, progress, closed, dan rejected.',
            'Mengetahui complaint yang overdue dan perlu intervensi cepat.',
            'Memantau analitik pelapor, tren laporan, dan distribusi jenis layanan.',
            'Membuat monitoring lebih objektif karena semua berbasis data.'
        )

    Add-BulletSlide -Presentation $presentation `
        -Title 'Top Laporan Membentuk Budaya Service Lebih Baik' `
        -Bullets @(
            'Data top pelapor menunjukkan siapa yang aktif membantu menjaga kualitas area kerja.',
            'Dengan sistem ini, semua orang dapat menjadi agen untuk perbaikan service.',
            'Informasi tersebut bukan untuk mencari kesalahan, tetapi untuk mempercepat deteksi masalah operasional.',
            'Ke depan, top pelapor dapat dipakai sebagai dasar pemberian reward dan apresiasi.'
        )

    Add-BulletSlide -Presentation $presentation `
        -Title 'Arah Lanjutan: Reward dan Feedback Service' `
        -Bullets @(
            'Pelapor yang aktif dan berkualitas dapat diberi reward sebagai bentuk partisipasi positif.',
            'Tim service juga dapat dievaluasi dari kecepatan respon, jumlah closed, dan feedback pengguna.',
            'Management dapat memiliki sistem apresiasi berbasis data, bukan asumsi.',
            'Feedback loop ini akan membantu menaikkan disiplin kerja sekaligus kepuasan user.'
        )

    Add-ImpactSlide -Presentation $presentation

    Add-BulletSlide -Presentation $presentation `
        -Title 'KPI yang Bisa Dipantau Management' `
        -Bullets @(
            'Jumlah laporan masuk per periode dan per jenis layanan.',
            'Persentase laporan open, progress, closed, dan rejected.',
            'Jumlah overdue dan lama rata-rata penyelesaian.',
            'Top pelapor, top area masalah, dan kualitas tindak lanjut tim service.',
            'Perbandingan sebelum dan sesudah aplikasi untuk melihat dampak nyata.'
        ) `
        -Footer 'Saran: gunakan masa evaluasi 1 sampai 3 bulan untuk baseline dan pembuktian hasil.'

    Add-BulletSlide -Presentation $presentation `
        -Title 'Kesimpulan untuk Management' `
        -Bullets @(
            'Aplikasi ini menjawab masalah lost report dan lemahnya monitoring layanan.',
            'Tahap 1 membuat user mudah melapor dan laporan tidak hilang.',
            'Tahap 2 memberi dashboard penuh untuk sub user operasional dan owner.',
            'Jika berjalan konsisten, kualitas service GA akan lebih cepat, lebih terukur, dan lebih dipercaya pengguna.',
            'Data dari sistem juga membuka peluang reward, feedback, dan continuous improvement.'
        )

    if (Test-Path $outputPath) {
        Remove-Item -LiteralPath $outputPath -Force
    }

    $presentation.SaveAs($outputPath)
    $presentation.Close()
    $powerPoint.Quit()

    Write-Output "Presentation created: $outputPath"
}
finally {
    if ($presentation -ne $null) {
        try { $presentation.Close() } catch {}
    }
    if ($powerPoint -ne $null) {
        try { $powerPoint.Quit() } catch {}
    }
}
