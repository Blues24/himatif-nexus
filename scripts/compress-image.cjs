#!/usr/bin/env node
// ponytail: requires `npm install sharp` before use; remove this check if dependency is committed
const { execSync } = require('child_process');
const path = require('path');

try {
  require('sharp');
} catch {
  console.error('[image-optimizer] sharp not installed — run: npm install sharp');
  process.exit(1);
}

const sharp = require('sharp');
const fs = require('fs');

const inputPath = process.argv[2];
if (!inputPath || !fs.existsSync(inputPath)) {
  console.error(`[image-optimizer] missing or invalid input: ${inputPath ?? '(none)'}`);
  process.exit(1);
}

const outDir = process.env.IMAGE_OPTIMIZER_OUT_DIR || path.join(process.cwd(), 'storage/temp/images');
if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });

const baseName = path.basename(inputPath, path.extname(inputPath));
const maxSize = parseInt(process.env.IMAGE_OPTIMIZER_MAX_SIZE || '1920', 10);
const quality = parseInt(process.env.IMAGE_OPTIMIZER_QUALITY || '85', 10);
const preferWebP = (process.env.IMAGE_OPTIMIZER_PREFER_WEBP || 'true') === 'true';
const webpThreshold = parseInt(process.env.IMAGE_OPTIMIZER_WEBP_THRESHOLD || '200', 10); // KB

async function optimize() {
  const metadata = await sharp(inputPath).metadata();
  const scale = Math.min(1, maxSize / (metadata.width || 1));
  const pipeline = sharp(inputPath).resize(Math.round(metadata.width * scale), Math.round(metadata.height * scale), { fit: 'inside' });

  let outPath;
  if (preferWebP && metadata.format !== 'png') {
    try {
      await pipeline.webp({ quality }).toFile(outDir + '/' + baseName + '.webp');
      outPath = outDir + '/' + baseName + '.webp';
    } catch {
      outPath = null;
    }
    if (outPath) {
      const sizeKB = fs.statSync(outPath).size / 1024;
      // keep original if WebP isn't meaningfully smaller
      if (sizeKB < webpThreshold) outPath = null;
    }
  }

  if (!outPath) {
    const ext = metadata.format === 'png' ? 'png' : 'jpeg';
    const fmt = ext === 'png'
      ? pipeline.png({ quality })
      : pipeline.jpeg({ quality });
    outPath = outDir + '/' + baseName + '.' + ext;
    await fmt.toFile(outPath);
  }

  const stats = fs.statSync(outPath);
  console.log(JSON.stringify({
    success: true,
    outputPath: outPath,
    inputSize: fs.statSync(inputPath).size,
    outputSize: stats.size,
    format: path.extname(outPath).replace('.', ''),
  }));
}

optimize().catch(err => {
  console.error('[image-optimizer] failed:', err.message);
  process.exit(1);
});
