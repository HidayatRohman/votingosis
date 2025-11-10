<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { usePage } from '@inertiajs/vue3';

interface Props {
  form: any;
  onSubmit: () => void;
  editingItem?: { id: number; foto?: string } | null;
  variant?: 'card' | 'plain';
  showActions?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'card',
  showActions: true,
});

const photoUrl = (path?: string) => (path ? `/storage/${path}` : undefined);
</script>

<template>
  <template v-if="props.variant !== 'plain'">
    <Card>
      <CardHeader>
        <CardTitle>Data Voting</CardTitle>
      </CardHeader>
      <CardContent class="grid gap-4">
        <div class="grid gap-2">
          <Label for="nis">NIS</Label>
          <Input id="nis" v-model="form.nis" placeholder="Masukkan NIS" />
          <p v-if="form.errors.nis" class="text-red-500 text-sm">{{ form.errors.nis }}</p>
        </div>

        <div class="grid gap-2">
          <Label for="nama">Nama</Label>
          <Input id="nama" v-model="form.nama" placeholder="Masukkan nama" />
          <p v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</p>
        </div>

        <div class="grid gap-2 grid-cols-1 md:grid-cols-2 gap-x-4">
          <div class="grid gap-2">
            <Label for="kelas">Kelas</Label>
            <Input id="kelas" v-model="form.kelas" placeholder="Masukkan kelas" />
            <p v-if="form.errors.kelas" class="text-red-500 text-sm">{{ form.errors.kelas }}</p>
          </div>
          <div class="grid gap-2">
            <Label for="jurusan">Jurusan</Label>
            <Input id="jurusan" v-model="form.jurusan" placeholder="Masukkan jurusan" />
            <p v-if="form.errors.jurusan" class="text-red-500 text-sm">{{ form.errors.jurusan }}</p>
          </div>
        </div>

        <div class="grid gap-2">
          <Label for="foto">Foto</Label>
          <Input id="foto" type="file" @change="(e:any)=> form.foto = e.target.files[0]" />
          <div v-if="editingItem?.foto" class="mt-2">
            <img :src="photoUrl(editingItem?.foto)" alt="Foto" class="h-24 rounded" />
          </div>
          <p v-if="form.errors.foto" class="text-red-500 text-sm">{{ form.errors.foto }}</p>
        </div>

        <div class="grid gap-2">
          <Label for="visi">Visi</Label>
          <Textarea id="visi" v-model="form.visi" placeholder="Tulis visi" />
          <p v-if="form.errors.visi" class="text-red-500 text-sm">{{ form.errors.visi }}</p>
        </div>

        <div class="grid gap-2">
          <Label for="misi">Misi</Label>
          <Textarea id="misi" v-model="form.misi" placeholder="Tulis misi" />
          <p v-if="form.errors.misi" class="text-red-500 text-sm">{{ form.errors.misi }}</p>
        </div>

        <div v-if="props.showActions" class="flex justify-end gap-2">
          <Button type="button" variant="default" @click="onSubmit">Simpan</Button>
        </div>
      </CardContent>
    </Card>
  </template>
  <template v-else>
    <div class="grid gap-4">
      <div class="grid gap-2">
        <Label for="nis">NIS</Label>
        <Input id="nis" v-model="form.nis" placeholder="Masukkan NIS" />
        <p v-if="form.errors.nis" class="text-red-500 text-sm">{{ form.errors.nis }}</p>
      </div>

      <div class="grid gap-2">
        <Label for="nama">Nama</Label>
        <Input id="nama" v-model="form.nama" placeholder="Masukkan nama" />
        <p v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</p>
      </div>

      <div class="grid gap-2 grid-cols-1 md:grid-cols-2 gap-x-4">
        <div class="grid gap-2">
          <Label for="kelas">Kelas</Label>
          <Input id="kelas" v-model="form.kelas" placeholder="Masukkan kelas" />
          <p v-if="form.errors.kelas" class="text-red-500 text-sm">{{ form.errors.kelas }}</p>
        </div>
        <div class="grid gap-2">
          <Label for="jurusan">Jurusan</Label>
          <Input id="jurusan" v-model="form.jurusan" placeholder="Masukkan jurusan" />
          <p v-if="form.errors.jurusan" class="text-red-500 text-sm">{{ form.errors.jurusan }}</p>
        </div>
      </div>

      <div class="grid gap-2">
        <Label for="foto">Foto</Label>
        <Input id="foto" type="file" @change="(e:any)=> form.foto = e.target.files[0]" />
        <div v-if="editingItem?.foto" class="mt-2">
          <img :src="photoUrl(editingItem?.foto)" alt="Foto" class="h-24 rounded" />
        </div>
        <p v-if="form.errors.foto" class="text-red-500 text-sm">{{ form.errors.foto }}</p>
      </div>

      <div class="grid gap-2">
        <Label for="visi">Visi</Label>
        <Textarea id="visi" v-model="form.visi" placeholder="Tulis visi" />
        <p v-if="form.errors.visi" class="text-red-500 text-sm">{{ form.errors.visi }}</p>
      </div>

      <div class="grid gap-2">
        <Label for="misi">Misi</Label>
        <Textarea id="misi" v-model="form.misi" placeholder="Tulis misi" />
        <p v-if="form.errors.misi" class="text-red-500 text-sm">{{ form.errors.misi }}</p>
      </div>

      <div v-if="props.showActions" class="flex justify-end gap-2">
        <Button type="button" variant="default" @click="onSubmit">Simpan</Button>
      </div>
    </div>
  </template>
</template>