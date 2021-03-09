#include <student/gpu.h>
 
#include <assert.h>
#include <math.h>
#include <stdio.h>
#include <string.h>
#include <iostream>
 
bool isPointInsideView(Vec4 const*const p){
  for(int i=0;i<3;++i){
    if(p->data[i] <= -p->data[3])return false;
    if(p->data[i] >= +p->data[3])return false;
  }
  return true;
}
 
void dividePerspectively(Vec4*const ndc,Vec4 const*const p){
  for(int a=0;a<3;++a)
    ndc->data[a] = p->data[a]/p->data[3];
  ndc->data[3] = p->data[3];
}
 
Vec4 fragPositionComputing(Vec4 const&p,uint32_t width,uint32_t height){
  Vec4 res;
  res.data[0] = (p.data[0]*.5f+.5f)*width;
  res.data[1] = (p.data[1]*.5f+.5f)*height;
  res.data[2] = p.data[2];
  res.data[3] = p.data[3];
  return res;
}
 
void vertexAttribute(GPU const*const gpu,GPUAttribute*const att,GPUVertexPullerHead const*const head,uint64_t vertexId){
  if(!head->enabled)return;
  GPUBuffer const*const buf = gpu_getBuffer(gpu,head->bufferId);
  uint8_t const*ptr = (uint8_t*)buf->data;
  uint32_t const offset = (uint32_t)head->offset;
  uint32_t const stride = (uint32_t)head->stride;
  uint32_t const size   = (uint32_t)head->type;
  memcpy(att->data,ptr+offset + vertexId*stride,size);
}
 
void lobotomizedVertexPuller(GPUInVertex*const vertex,GPUVertexPuller const*const vao,GPU const*const gpu,uint32_t vertexShaderInv){
  uint32_t vertexId = vertexShaderInv;
  vertex->gl_VertexID = vertexId;
  if (gpu_isBuffer(gpu, vao->indices.bufferId)) {
    const GPUBuffer *hlp = gpu_getBuffer(gpu, vao->indices.bufferId);
    if (vao->indices.type == UINT8) vertex->gl_VertexID = ((uint8_t*)hlp->data)
		[vertexId];
    if (vao->indices.type == UINT16) vertex->gl_VertexID = ((uint16_t*)hlp->data)
		[vertexId];
    if (vao->indices.type == UINT32) vertex->gl_VertexID = ((uint32_t*)hlp->data)
		[vertexId];
  }
 
  vertexAttribute(gpu,vertex->attributes,vao->heads,vertex->gl_VertexID);
  for (int i = 1; i <= 7; i++)
  	vertexAttribute(gpu, vertex->attributes + i, vao->heads + i, vertex->gl_VertexID);
  
}
 
void lobotomizedPointRasterization(GPUInFragment*const inFrag,Vec4 ndc,GPU*const gpu,GPUOutVertex const*const outVertex){
  Vec4 coord = fragPositionComputing(ndc,gpu->framebuffer.width,gpu->framebuffer.height);
  inFrag->gl_FragCoord = coord;
  memcpy(inFrag->attributes[0].data,outVertex->attributes[0].data,sizeof(Vec4));
}
 
void lobotomizedFragmentOperation(GPUOutFragment const*const outFrag,GPU*const gpu,Vec4 ndc){
  Vec4 coord = fragPositionComputing(ndc,gpu->framebuffer.width,gpu->framebuffer.height);
  GPUFramebuffer*const frame = &gpu->framebuffer;
  if(coord.data[0] < 0 || coord.data[0] >= frame->width)
	  return;
  if(coord.data[1] < 0 || coord.data[1] >= frame->height)
	  return;
  if(isnan(coord.data[0]))
	  return;
  if(isnan(coord.data[1]))
	  return;
  uint32_t const pixCoord = frame->width*(int)coord.data[1]+(int)coord.data[0];
 
  frame->color[pixCoord] = outFrag->gl_FragColor;
}
 
/// \addtogroup gpu_side Implementace vykreslovacího řetězce - vykreslování trojúhelníků
/// @{
 
/**
 * @brief This function should draw triangles
 *
 * @param gpu gpu
 * @param nofVertices number of vertices
 */
void gpu_drawTriangles(GPU *const gpu, uint32_t nofVertices)
{
 
  /// \todo Naimplementujte vykreslování trojúhelníků.
  /// nofVertices - počet vrcholů
  /// gpu - data na grafické kartě
  /// Vašim úkolem je naimplementovat chování grafické karty.
  /// Úkol je složen:
  /// 1. z implementace Vertex Pulleru
  /// 2. zavolání vertex shaderu pro každý vrchol
  /// 3. rasterizace
  /// 4. zavolání fragment shaderu pro každý fragment
  /// 5. zavolání per fragment operací nad fragmenty (depth test, zápis barvy a hloubky)
  /// Více v připojeném videu.
  (void)gpu;
  (void)nofVertices;
  GPUProgram      const* prog = gpu_getActiveProgram(gpu);
  GPUVertexPuller const* vao = gpu_getActivePuller (gpu);
 
  GPUVertexShaderData   vertexData;
  GPUFragmentShaderData fragmentData;
 
  GPUVertexShaderData   vertexData1;
  GPUVertexShaderData   vertexData2;
 
  vertexData.uniforms = &prog->uniforms;
  vertexData1.uniforms = &prog->uniforms;
  vertexData2.uniforms = &prog->uniforms;
 
  for(uint32_t v=0;v<nofVertices;v+=3){
 
    lobotomizedVertexPuller(&vertexData.inVertex,vao,gpu,v);
    lobotomizedVertexPuller(&vertexData1.inVertex,vao,gpu,v+1);
    lobotomizedVertexPuller(&vertexData2.inVertex,vao,gpu,v+2);
 
    prog->vertexShader(&vertexData);
    prog->vertexShader(&vertexData1);
    prog->vertexShader(&vertexData2);
 
    Vec4 pos;
    Vec4 pos1;
    Vec4 pos2;
    copy_Vec4(&pos,&vertexData.outVertex.gl_Position);
    copy_Vec4(&pos,&vertexData1.outVertex.gl_Position);
    copy_Vec4(&pos,&vertexData2.outVertex.gl_Position);
 
    Vec4 ndc;
    Vec4 ndc1;
    Vec4 ndc2;
    dividePerspectively(&ndc,&pos);
    dividePerspectively(&ndc1,&pos);
    dividePerspectively(&ndc2,&pos);
 
    lobotomizedPointRasterization(&fragmentData.inFragment,ndc,gpu,&vertexData.outVertex);
 
    prog->fragmentShader(&fragmentData);
 
    lobotomizedFragmentOperation(&fragmentData.outFragment,gpu,ndc);
  }
}
 
/// @}